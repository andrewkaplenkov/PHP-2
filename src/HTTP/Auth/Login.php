<?php

namespace App\HTTP\Auth;

use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\AuthControllerInterface;
use App\HTTP\Actions\ActionInterface;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\AuthToken;
use DateTimeImmutable;
use Exception;

class Login implements ActionInterface
{
    public function __construct(
        private PasswordIdentification $passwordId,
        private AuthControllerInterface $authController
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $user = $this->passwordId->user($request);
        } catch (Exception $e) {
            return new UnsuccessfullResponse($e->getMessage());
        }

        $token = new AuthToken(
            bin2hex(random_bytes(40)),
            $user->id(),
            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->authController->save($token);

        return new SuccessfullResponse([
            'token' => (string)$token->token()
        ]);
    }
}
