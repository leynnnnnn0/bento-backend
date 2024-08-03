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
        if($this->destroy($db, $data)) {
            return json_encode(['success' => true]);
        }else {
            $query = "INSERT INTO votes (likedBy_id, post_id) VALUES (:likedBy_id, :post_id)";
            $statement = $db->query($query, [':likedBy_id' => $data['user_id'], ':post_id' => $data['post_id']]);
            return json_encode(['success' => $statement->rowCount() > 0]);
        }

    }

    public function destroy(Database $db, array $data): bool
    {
        $query = "SELECT * FROM votes WHERE likedBy_id = :likedBy_id AND post_id = :post_id";
        $statement = $db->query($query, ['likedBy_id' => $data['user_id'], 'post_id' => $data['post_id']]);
        $result = $statement->fetch();
        if(!empty($result)) {
            $query = "DELETE FROM votes WHERE id = :id";
            $db->query($query, [':id' => $result['id']]);
            return true;
        }
        return false;


    }



}