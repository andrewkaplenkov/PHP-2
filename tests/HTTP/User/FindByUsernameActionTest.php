<?php

namespace App\Test\HTTP\User;

use App\Controllers\User\UserController;
use App\Controllers\User\UserControllerInterface;
use App\Exceptions\NotFoundException;
use App\HTTP\Actions\User\FindByUsername;
use App\HTTP\Request\Request;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\User;
use App\Models\UUID;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{

	private function usersRepository(array $users): UserControllerInterface
	{
		// В конструктор анонимного класса передаём массив пользователей
		return new class($users) implements UserControllerInterface
		{
			public function __construct(
				private array $users
			) {
			}
			public function makeUser(User $user): void
			{
			}
			public function findById(UUID $uuid): User
			{
				throw new NotFoundException("Not found");
			}
			public function findByUserName(string $username): User
			{
				foreach ($this->users as $user) {
					if ($user instanceof User && $username === $user->username()) {
						return $user;
					}
				}
				throw new NotFoundException("Not found");
			}

			public function fetchUser(PDOStatement $statement, string $searchQuery): User
			{
				throw new NotFoundException("Not found");
			}

			public function deleteUser(string $userName): void
			{
			}
		};
	}


	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */

	public function testItReturnsErrorResponseIfNoUsernameProvided(): void
	{
		$request = new Request([], [], '');

		$userController = $this->usersRepository([]);

		$action = new FindByUsername($userController);

		$response = $action->handle($request);

		$this->assertInstanceOf(UnsuccessfullResponse::class, $response);

		$this->expectOutputString('{"success":false,"errorReason":"Cannot get param from the request: username"}');

		$response->send();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */

	public function testItReturnsErrorResponseIfUserNotFound(): void
	{
		$request = new Request(['username' => 'ivan'], [], '');

		$userController = $this->usersRepository(['username' => 'ivan']);

		$action = new FindByUsername($userController);
		$response = $action->handle($request);

		$this->assertInstanceOf(UnsuccessfullResponse::class, $response);

		$this->expectOutputString('{"success":false,"errorReason":"Not found"}');

		$response->send();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */

	// public function testItReturnsSuccessfulResponse(): void
	// {
	// 	$request = new Request(['username' => 'ivan'], [], '');

	// 	$usersRepository = $this->usersRepository([
	// 		new User(
	// 			UUID::random(),
	// 			'ivan',
	// 			'ivan',
	// 			'ivan'
	// 		)
	// 	]);

	// 	$action = new FindByUsername($usersRepository);
	// 	$response = $action->handle($request);

	// 	$this->assertInstanceOf(SuccessfulResponse::class, $response);

	// 	$this->expectOutputString('{"success":true,"data":{"username":"ivan","firstname":"ivan","lastname":"ivan"}}');
	// 	$response->send();
	// }
}
