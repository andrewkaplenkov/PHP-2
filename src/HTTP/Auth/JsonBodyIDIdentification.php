<?php

namespace App\HTTP\Auth;

use App\Controllers\User\UserControllerInterface;
use App\Exceptions\HTTPException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\HTTP\Request\Request;
use App\Models\User;
use App\Models\UUID;
use Exception;

class JsonBodyIDIdentification implements AuthInterface
{
	public function __construct(
		private UserControllerInterface $userController
	) {
	}

	public function user(Request $request): User
	{
		try {
			$userId = new UUID($request->JsonBodyField('user_id'));
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

		try {
			return $this->userController->findById($userId);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
