<?php

namespace App\Tests\Utils;

use App\Controllers\User\DummyUserController;
use App\Controllers\User\UserControllerInterface;
use App\Controllers\User\Utils\Arguments;
use App\Controllers\User\Utils\CreateUserCommand;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\AppException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;

use App\Models\User;
use App\Models\UUID;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{

	public function makeDummyUserController(): UserControllerInterface
	{
		return new class implements UserControllerInterface
		{

			public function makeUser(User $user): void
			{
				throw new AlreadyExistsException("User already exists: Ivan");
			}

			public function findById(UUID $id): User
			{
				throw new NotFoundException("User not found: $id");
			}

			public function findByUserName(string $userName): User
			{
				throw new NotFoundException("User not found: $userName");
			}

			public function fetchUser(PDOStatement $statement, string $searchQuery): User
			{
				throw new NotFoundException("User not found");
			}
		};
	}

	public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
	{
		$command = new CreateUserCommand($this->makeDummyUserController());

		$this->expectException(AlreadyExistsException::class);
		$this->expectExceptionMessage("User already exists: Ivan");
		$command->handle(new Arguments([
			'userName' => 'Ivan',
			'firstName' => 'Ivan',
			'lastName' => 'Ivan'
		]));
	}

	public function testItRequiresFirstName(): void
	{
		$command = new CreateUserCommand($this->makeDummyUserController());

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Empty value for: firstName");

		$command->handle(new Arguments(['userName' => 'Ivan']));
	}

	public function testItRequiresLastName(): void
	{
		$command = new CreateUserCommand($this->makeDummyUserController());

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Empty value for: lastName");

		$command->handle(new Arguments([
			'userName' => 'Ivan',
			'firstName' => 'Ella'
		]));
	}

	public function testItSavesUserToDatabase(): void
	{

		$userController = new class implements UserControllerInterface
		{
			private bool $called;

			public function makeUser(User $user): void
			{
				$this->called = true;
			}

			public function findById(UUID $id): User
			{
				throw new NotFoundException("User not found");
			}

			public function findByUserName(string $userName): User
			{
				throw new NotFoundException("User not found");
			}

			public function fetchUser(PDOStatement $statement, string $searchQuery): User
			{
				throw new NotFoundException("User not found");
			}

			public function wasCalled(): bool
			{
				return $this->called;
			}
		};

		$command = new CreateUserCommand($userController);

		$command->handle(new Arguments([
			'userName' => 'Ivan',
			'firstName' => 'Ivan',
			'lastName' => 'Ivan'
		]));

		$this->assertTrue($userController->wasCalled());
	}
}
