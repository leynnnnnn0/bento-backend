<?php
header('Content-Type: application/json');
require_once 'Core/Application.php';
require_once 'controllers/UserController.php';


function dd($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

$config = [
    'database' => [
        'dsn' => 'mysql:host=localhost;dbname=bento_box',
        'user' => 'root',
        'password' => '',
    ]
];
$app = new Application($config, __DIR__);

$app->router->get('/', function(){
   return 'Hello World';
});

$app->router->get('/users', [UserController::class, 'index']);


$app->run();