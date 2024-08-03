<?php

class VoteController
{
    public function store()
    {
        $db = Application::$app->db;
        // Get the raw POST data from the input stream
        $rawData = file_get_contents('php://input');
        // Decode the JSON data into a PHP associative array
        $data = json_decode($rawData, true);
        $query = "INSERT INTO votes (likedBy_id, post_id) VALUES (:likedBy_id, :post_id)";
        $statement = $db->query($query, [':likedBy_id' => $data['user_id'], ':post_id' => $data['post_id']]);
        return json_encode(['success' => $statement->rowCount() > 0]);
    }
}