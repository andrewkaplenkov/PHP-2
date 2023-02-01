<?php

namespace App\Controllers\Comment;

use App\Controllers\Post\PostControllerInterface;
use App\Controllers\User\UserControllerInterface;
use App\Exceptions\NotFoundException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\UUID;
use PDO;

class CommentController implements CommentControllerInterface
{
	private PDO $connection;

	public function __construct(PDO $connection)
	{
		$this->connection = $connection;
	}

	public function makeComment(Comment $comment): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO comments(id, post_id, user_id, comment) VALUES(:id, :post_id, :user_id, :comment)'
		);

		$statement->execute([
			'id' => $comment->getId(),
			'post_id' => $comment->getPost()->getId(),
			'user_id' => $comment->getUser()->getId(),
			'comment' => $comment->getComment()
		]);
	}

	public function getCommentById(UUID $id, PostControllerInterface $postController, UserControllerInterface $userController)
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM comments WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);

		$result = $statement->fetch();

		if (!$result) {
			throw new NotFoundException("Comment not found: $id");
		}

		return new Comment(
			$id,
			$postController->getPostById(new UUID($result['post_id']), $userController),
			$userController->findById(new UUID($result['user_id'])),
			$result['comment']
		);
	}
}
