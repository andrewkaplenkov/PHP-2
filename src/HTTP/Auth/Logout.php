<?php

namespace App\HTTP\Auth;

use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\AuthControllerInterface;
use App\HTTP\Actions\ActionInterface;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use Exception;

class Logout implements ActionInterface
{
    public function __construct(
        private AuthController $authController,
        private PasswordIdentification $passwordId,


    ) {
    }

    public function handle(Request $request): Response
    {

        try {
            $user = $this->passwordId->user($request);
        } catch (Exception $e) {
            return new UnsuccessfullResponse($e->getMessage());
        }

        try {
            $token = $request->JsonBodyField('token');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        $this->authController->makeTokenExpired($token);

        return new SuccessfullResponse([
            'status' => 'logged out'
        ]);
    }
}
