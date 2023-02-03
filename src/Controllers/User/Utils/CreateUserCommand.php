<?php

namespace App\Controllers\User\Utils;

use App\Controllers\User\UserControllerInterface;
use App\Exceptions\AlreadyExistsException;
use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Models\UUID;

class CreateUserCommand
{
	private UserControllerInterface $userController;

	public function __construct(UserControllerInterface $userController)
	{
		$this->userController = $userController;
	}

	public function handle(Arguments $arguments): void
	{
		$userName = $arguments->getArgumentValue('userName');

		if ($this->userExists($userName)) {
			throw new AlreadyExistsException("User already exists: $userName");
		}

		$this->userController->makeUser(new User(
			UUID::random(),
			$userName,
			$arguments->getArgumentValue('firstName'),
			$arguments->getArgumentValue('lastName')
		));
	}

	public function userExists(string $userName): bool
	{
		try {
			$this->userController->findByUserName($userName);
		} catch (NotFoundException $e) {
			echo $e->getMessage();
			return false;
		}

		return true;
	}
}
