<?php


namespace App\Tests\Controllers;

use App\Controllers\Comment\CommentController;
use App\Controllers\Post\PostController;
use App\Controllers\User\UserController;
use App\Exceptions\NotFoundException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\UUID;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class CommentControllerTest extends TestCase
{

	public function testItSavesCommentToDatabase(): void
	{
		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);

		$statementMock
			->expects($this->once())
			->method('execute')
			->with([
				'id' => '123e4567-e89b-12d3-a456-426614174000',
				'post_id' => '9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7',
				'user_id' => '23cc79eb-3130-436e-bf85-a69191dec4d0',
				'comment' => 'TestComment'
			]);

		$connectionStub->method('prepare')->willReturn($statementMock);

		$commentController = new CommentController($connectionStub);

		$commentController->makeComment(new Comment(
			new UUID('123e4567-e89b-12d3-a456-426614174000'),
			new Post(
				new UUID('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7'),
				new User(
					new UUID('7068b9ca-506c-4ea3-a6d6-8f8f383a2fb2'),
					'Ivan',
					'Ivan',
					'Ivan'
				),
				'TestTitle',
				'TestText'
			),
			new User(
				new UUID('23cc79eb-3130-436e-bf85-a69191dec4d0'),
				'Sveta',
				'Sveta',
				'Sveta'
			),
			'TestComment'
		));
	}

	public function testItThrowsAnExceptionWhenCommentNotFound(): void
	{
		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);

		$statementMock->method('fetch')->willReturn(false);
		$connectionStub->method('prepare')->willReturn($statementMock);

		$commentController = new CommentController($connectionStub);
		$postController = new PostController($connectionStub);
		$userController = new UserController($connectionStub);


		$this->expectException(NotFoundException::class);
		$this->expectExceptionMessage("Comment not found: d74cf51b-f782-4fd1-8f9b-d66114cabb31");
		$commentController->getCommentById(new UUID('d74cf51b-f782-4fd1-8f9b-d66114cabb31'), $postController, $userController);
	}

	public function testItFindsCommentById(): void
	{

		$connectionStub = $this->createStub(PDO::class);
		$statementMock = $this->createMock(PDOStatement::class);

		$postController = new PostController($connectionStub);
		$userController = new UserController($connectionStub);
		$commentController = new CommentController($connectionStub);

		$statementMock->method('fetch')->willReturn([
			'id' => '9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7',
			'user_id' => '7068b9ca-506c-4ea3-a6d6-8f8f383a2fb2',
			'post_id' => '71c19122-43a8-4807-bd2e-018ee556fc84',
			'comment' => 'TestComment',
			'title' => 'TestTitle',
			'text' => 'TestText',
			'userName' => 'Ivan',
			'firstName' => 'Ivan',
			'lastName' => 'Ivan'
		]);

		$connectionStub->method('prepare')->willReturn($statementMock);

		$comment = $commentController->getCommentById(new UUID('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7'), $postController, $userController);

		$this->assertSame('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7', (string)$comment->getId());
	}
}
