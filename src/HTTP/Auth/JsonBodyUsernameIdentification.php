<?php


namespace App\HTTP\Auth;

use App\Controllers\User\UserControllerInterface;
use App\HTTP\Request\Request;
use App\Models\User;
use Exception;

class JsonBodyUsernameIdentification implements AuthInterface
{
	public function __construct(
		private UserControllerInterface $userController
	) {
	}

	public function user(Request $request): User
	{
		try {
			$username = $request->JsonBodyField('username');
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		try {
			return $this->userController->findByUserName($username);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
