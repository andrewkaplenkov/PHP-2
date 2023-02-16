<?php

namespace App\Controllers\Auth;

use App\Models\AuthToken;


interface AuthControllerInterface
{
    public function save(AuthToken $authToken): void;

    public function get(string $token): AuthToken;
}
