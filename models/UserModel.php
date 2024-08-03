<?php

require_once Application::$rootPath . '/Core/Model.php';
class UserModel extends Model
{
    public string $username;
    public string $email;
    public string $password;
    public string $password_confirmation;

    public function table(): string
    {
       return 'users';
    }

    public function attributes(): array
    {
        return [
            'username' => ['required', ['min', 3]],
            'email' => ['required', 'email', 'unique'],
            'password' => ['required', ['min', 8], 'match'],
            'password_confirmation' => ['required']
        ];
    }
}