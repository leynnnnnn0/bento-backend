<?php
require_once 'Core/Application.php';

$config = [
    'database' => [
        'dsn' => 'mysql:host=localhost;dbname=bento_box',
        'user' => 'root',
        'password' => '',
    ]
];
$app = new Application($config, __DIR__);

$app->db->initializeMigration();