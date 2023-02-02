<?php

namespace App\Tests\Controllers;

use App\Controllers\Post\PostController;
use App\Controllers\Post\PostControllerInterface;
use App\Controllers\User\UserController;
use App\Controllers\User\UserControllerInterface;
use App\Exceptions\NotFoundException;
use App\Models\Post;
use App\Models\User;
use App\Models\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class PostControllerTest extends TestCase
{

	public function testItSavesPostToDatabase(): void
	{
		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);

		$statementMock
			->expects($this->once())
			->method('execute')
			->with([
				'id' => '123e4567-e89b-12d3-a456-426614174000',
				'user_id' => '7068b9ca-506c-4ea3-a6d6-8f8f383a2fb2',
				'title' => 'TestTitle',
				'text' => 'TestText'
			]);

		$connectionStub->method('prepare')->willReturn($statementMock);

		$postController = new PostController($connectionStub);

		$postController->makePost(new Post(
			new UUID('123e4567-e89b-12d3-a456-426614174000'),
			new User(
				new UUID('7068b9ca-506c-4ea3-a6d6-8f8f383a2fb2'),
				'Ivan',
				'Ivan',
				'Ivan'
			),
			'TestTitle',
			'TestText'
		));
	}

	public function testItFindsPostById(): void
	{

		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);

		$postController = new PostController($connectionStub);
		$userController = new UserController($connectionStub);

		$statementMock->method('fetch')->willReturn([
			'id' => '9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7',
			'user_id' => '7068b9ca-506c-4ea3-a6d6-8f8f383a2fb2',
			'title' => 'TestTitle',
			'text' => 'TestText',
			'userName' => 'Ivan',
			'firstName' => 'Ivan',
			'lastName' => 'Ivan'
		]);

		$connectionStub->method('prepare')->willReturn($statementMock);

		$post = $postController->getPostById(new UUID('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7'), $userController);

		$this->assertSame('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7', (string)$post->getId());
	}

	public function testItThrowsAnExceptionWhenPostNotFound(): void
	{
		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);

		$statementMock->method('fetch')->willReturn(false);
		$connectionStub->method('prepare')->willReturn($statementMock);

		$postController = new PostController($connectionStub);
		$userController = new UserController($connectionStub);

		$this->expectException(NotFoundException::class);
		$this->expectExceptionMessage("Post not found: 9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7");
		$postController->getPostById(new UUID('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7'), $userController);
	}
}
