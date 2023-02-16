<?php

namespace App\HTTP\Auth;

use App\Controllers\Auth\AuthControllerInterface;
use App\Controllers\User\UserControllerInterface;
use App\HTTP\Request\Request;
use App\Models\User;
use DateTimeImmutable;
use Exception;

class BearerTokenAuth implements TokenAuthInterface
{
    private const HEADER_PREFIX = 'Bearer ';

    public function __construct(
        private AuthControllerInterface $authController,
        private UserControllerInterface $userController
    ) {
    }

    public function user(Request $request): User
    {
        try {
            $header = $request->header('Authorization');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new Exception("Malformed token: [$header]");
        }

        $token = mb_substr($header, strlen(self::HEADER_PREFIX));

        try {
            $authToken = $this->authController->get($token);
        } catch (Exception) {
            throw new Exception("Bad token: [$token]");
        }

        if ($authToken->exp() <= new DateTimeImmutable()) {
            throw new Exception("Token expired: [$token]");
        }

        $user_id = $authToken->user_id();

        return $this->userController->findById($user_id);
    }
}
