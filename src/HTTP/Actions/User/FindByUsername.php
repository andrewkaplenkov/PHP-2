<?php


namespace App\HTTP\Actions\User;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\User\UserControllerInterface;
use App\Exceptions\HTTPException;
use App\Exceptions\NotFoundException;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;

class FindByUsername implements ActionInterface
{
	public function __construct(
		private UserControllerInterface $userController
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$username = $request->query('username');
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$user = $this->userController->findByUserName($username);
		} catch (NotFoundException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'username' => $user->username(),
			'firstname' => $user->firstName(),
			'lastname' => $user->lastName()
		]);
	}
}
