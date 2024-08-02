<?php

require_once 'Application.php';
class Database
{
    private $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new PDO($config['dsn'], $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function query(string $query, array $params = [])
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        return $statement;
    }

    public function initializeMigration()
    {
        $this->createMigration();
        // Get the migrated files
        $migratedFiles = $this->migratedFiles();
        // Get the migrations
        $migrations = scandir(Application::$rootPath . '/migrations');
        // Get the new files from migrations that is not migrated yet
        $toMigrate = array_diff($migrations, $migratedFiles);
        $migrationsList = [];
        foreach ($toMigrate as $migration) {
            if($migration === '.' || $migration === '..') continue;
            require_once Application::$rootPath . '/migrations/' . $migration;
            $fileName = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $fileName();
            $this->log('Applying migrations...');
            $instance->up();
            $migrationsList[] = $migration;
        }
        // Migrate files
        if(empty($migrationsList)) {
            $this->log('No migration to apply');
            return;
        }
        $this->insertMigrations($migrationsList);
        $this->log('Migration applied');
    }

    public function createMigration()
    {
        $query = "CREATE TABLE IF NOT EXISTS migration (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";
        $this->query($query);
    }

    public function migratedFiles()
    {
        $query = "SELECT migration FROM migration";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insertMigrations($migrations)
    {
        $migrations = implode(", ", array_map(fn($item) => "('$item')", $migrations));
        $query = "INSERT INTO migration (migration) VALUES $migrations";
        $this->query($query);
    }

    public function log($message)
    {
        echo $message . PHP_EOL;
    }

}