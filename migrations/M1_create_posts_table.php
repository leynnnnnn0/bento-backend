<?php

require_once Application::$rootPath . '/Core/Migration.php';

class M1_create_posts_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        $query = "CREATE TABLE IF NOT EXISTS posts (
                  id INT(11) AUTO_INCREMENT PRIMARY KEY,
                  user_id INT NOT NULL,        
                  body TEXT NOT NULL,
                  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (user_id) REFERENCES users(id)
                  )";
        $db->query($query);
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
}