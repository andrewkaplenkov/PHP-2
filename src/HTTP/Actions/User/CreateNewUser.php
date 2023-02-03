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

class CreateNewUser implements ActionInterface
{
	public function __construct(
		private UserControllerInterface $userController
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
			return new SuccessfullResponse([
				'id' => (string)$user->id(),
				'username' => $user->username(),
				'status' => 'created'
			]);
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse("User not created!");
		}
	}
}
