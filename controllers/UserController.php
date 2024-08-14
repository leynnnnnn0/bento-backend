<?php

class UserController
{
    public function index()
    {
        $db = Application::$app->db;
        $query = "SELECT * FROM users";
        $result = $db->query($query);
        return json_encode($result->fetchAll());
    }

    public function store()
    {
        require_once Application::$rootPath . '/models/UserModel.php';
        $db = Application::$app->db;
        // Get the raw POST data from the input stream
        $rawData = file_get_contents('php://input');
        // Decode the JSON data into a PHP associative array
        $data = json_decode($rawData, true);
        $user = new UserModel();
        $user->loadData($data);
        if($user->validate())
        {
            return json_encode(['success' => false, 'errors' => $user->errors]);
        }
        $query = "INSERT INTO users (username, email, password) values (:username, :email, :password)";
        $statement = $db->query($query, [':username' => $user->username, ':email' => $user->email, ':password' => password_hash($user->password, PASSWORD_BCRYPT)]);
        return json_encode(['success' => $statement->rowCount() > 0]);

    }

}