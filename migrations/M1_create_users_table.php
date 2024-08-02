<?php

require_once Application::$rootPath . '/Core/Migration.php';

class M1_create_users_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        $query = "CREATE TABLE IF NOT EXISTS users (
                  id INT(11) AUTO_INCREMENT PRIMARY KEY,
                  username VARCHAR(255) NOT NULL,
                  email VARCHAR(255) NOT NULL UNIQUE,
                  password VARCHAR(255) NOT NULL
                  )";
        $db->query($query);
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
}