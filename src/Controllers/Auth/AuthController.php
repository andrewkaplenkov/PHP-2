<?php

namespace App\Controllers\Auth;

use App\Models\AuthToken;
use App\Models\UUID;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use PDO;

class AuthController implements AuthControllerInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(AuthToken $authToken): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO tokens (token, user_id, expires_on) 
            VALUES (:token, :user_id, :expires_on)'
        );

        $statement->execute([
            'token' => $authToken->token(),
            'user_id' => (string)$authToken->user_id(),
            'expires_on' => $authToken->exp()->format(DateTimeInterface::ATOM)
        ]);
    }

    public function get(string $token): AuthToken
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM tokens WHERE token = :token'
        );

        $statement->execute([
            'token' => $token
        ]);

        $result = $statement->fetch();

        if (!$result) {
            throw new Exception("Token not found");
        }

        return new AuthToken(
            $result['token'],
            new UUID($result['user_id']),
            new DateTimeImmutable($result['expires_on'])
        );
    }

    public function makeTokenExpired(string $token): void
    {
        $statement = $this->connection->prepare(
            'UPDATE tokens SET expires_on = :expires_on WHERE token = :token'
        );

        $statement->execute([
            'token' => $token,
            'expires_on' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM)
        ]);
    }
}
