<?php

require_once Application::$rootPath . '/Core/Migration.php';

class M1_create_votes_table extends Migration
{
    public function up()
    {
        $db = Application::$app->db;
        $query = "CREATE TABLE IF NOT EXISTS votes (
                  id INT AUTO_INCREMENT PRIMARY KEY,
                  likedBy_id INT NOT NULL,
                  post_id INT NOT NULL,
                  vote VARCHAR(255) NOT NULL DEFAULT 'upVote',
                  FOREIGN KEY (likedBy_id) REFERENCES users(id),
                  FOREIGN KEY (post_id) REFERENCES posts(id)
                  )";
        $db->query($query);
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
}