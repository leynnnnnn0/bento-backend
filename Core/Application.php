<?php

require_once 'Database.php';
require_once 'Router.php';
class Application
{
    public Database $db;
    public Router $router;
    public static string $rootPath;
    public static Application $app;

    public function __construct(array $config, string $rootPath)
    {
        $this->db = new Database($config['database']);
        $this->router = new Router();
        self::$rootPath = $rootPath;
        self::$app = $this;
    }

    public function run()
    {
        echo $this->router->resolve();
    }

}