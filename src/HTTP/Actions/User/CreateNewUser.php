<?php

namespace App\HTTP\Actions\User;

use App\Controllers\User\UserControllerInterface;
use App\Exceptions\HTTPException;
use App\HTTP\Actions\ActionInterface;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\User;
use App\Models\UUID;
use Exception;
use Psr\Log\LoggerInterface;

class CreateNewUser implements ActionInterface
{
	public function __construct(
		private UserControllerInterface $userController,
		private LoggerInterface $logger
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$user = new User(
				UUID::random(),
				$request->JsonBodyField('username'),
				$request->JsonBodyField('firstname'),
				$request->JsonBodyField('lastname')
			);
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->userController->makeUser($user);
			$this->logger->info("User created " . $user->username());
			return new SuccessfullResponse([
				'id' => (string)$user->id(),
				'username' => $user->username(),
				'status' => 'created'
			]);
		} catch (Exception $e) {
			$this->logger->error("User alrady exists " . $user->username());
			return new UnsuccessfullResponse("User not created!");
		}
	}
}
