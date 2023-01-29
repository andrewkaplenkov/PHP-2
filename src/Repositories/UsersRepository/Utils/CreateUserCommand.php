<?php


namespace App\Repositories\UsersRepository\Utils;

use App\Exceptions\CommandException;
use App\Exceptions\UserNotFoundException;
use App\Models\User\Person;
use App\Models\UUID\UUID;
use App\Repositories\UsersRepository\UsersRepository;

class CreateUserCommand
{
	private UsersRepository $usersRepository;

	public function __construct(UsersRepository $usersRepository)
	{
		$this->usersRepository = $usersRepository;
	}

	public function handle(Arguments $arguments): void
	{

		$userName = $arguments->getArgument('user_name');

		if ($this->userExistsCheck($userName)) {
			throw new CommandException("User aldready exists: $userName");
		}

		$this->usersRepository->makeUser(new Person(
			UUID::randomUUID(),
			$userName,
			$arguments->getArgument('first_name'),
			$arguments->getArgument('last_name')
		));
	}

	public function userExistsCheck(string $userName): bool
	{

		try {
			$this->usersRepository->findByUserName($userName);
		} catch (UserNotFoundException $error) {
			return false;
		}

		return true;
	}
}
