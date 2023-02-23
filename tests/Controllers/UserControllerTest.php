<?php

namespace App\Tests\Controllers;

use App\Controllers\User\UserController;
use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Models\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

// class UserControllerTest extends TestCase
// {

// 	public function testItThrowsAnExceptionWhenUserNotFound(): void
// 	{
// 		$connectionStub = $this->createStub(PDO::class);
// 		$statementStub = $this->createStub(PDOStatement::class);

// 		$statementStub->method('fetch')->willReturn(false);

// 		$connectionStub->method('prepare')->willReturn($statementStub);

// 		$userController = new UserController($connectionStub);

// 		$this->expectException(NotFoundException::class);
// 		$this->expectExceptionMessage("User not found: Ivan");

// 		$userController->findByUserName('Ivan');
// 	}

// 	public function testItSavesUserToDatabase(): void
// 	{
// 		$connectionStub = $this->createStub(PDO::class);
// 		$statementMock = $this->createMock(PDOStatement::class);

// 		$statementMock
// 			->expects($this->once())
// 			->method('execute')
// 			->with([
// 				'id' => '123e4567-e89b-12d3-a456-426614174000',
// 				'userName' => 'Ivan',
// 				'firstName' => 'Ivan',
// 				'lastName' => 'Ivan'
// 			]);

// 		$connectionStub->method('prepare')->willReturn($statementMock);

// 		$userController = new UserController($connectionStub);

// 		$userController->makeUser(new User(
// 			new UUID('123e4567-e89b-12d3-a456-426614174000'),
// 			'Ivan',
// 			'Ivan',
// 			'Ivan'
// 		));
// 	}
// }
