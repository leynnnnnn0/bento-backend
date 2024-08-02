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
}