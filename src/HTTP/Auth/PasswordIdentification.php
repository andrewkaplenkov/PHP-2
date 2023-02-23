<?php


namespace App\HTTP\Auth;
use App\Controllers\User\UserControllerInterface;
use App\HTTP\Request\Request;
use App\Models\User;
use Exception;

class PasswordIdentification implements PasswordAuthInterface {
    public function __construct(
        private UserControllerInterface $userController
    ) {

    }

    public function user(Request $request): User
    {
        try {
            $username = $request->JsonBodyField('username');
            $password = $request->JsonBodyField('password');
            $user = $this->userController->findByUserName($username );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }


        if (!$user->passwordCheck($password)){
            throw new Exception("Wrong password $password");
        }

        return $user;
    }

}
    