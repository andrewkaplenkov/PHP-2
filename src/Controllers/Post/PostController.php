<?php

namespace App\Controllers\Post;

use App\Controllers\User\UserControllerInterface;
use App\Exceptions\NotFoundException;
use App\Models\Post;
use App\Models\UUID;
use PDO;
use Stringable;

class PostController implements PostControllerInterface
{
	private PDO $connection;

	public function __construct(PDO $connection)
	{
		$this->connection = $connection;
	}

	public function makePost(Post $post): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO posts(id, user_id, title, text) VALUES(:id, :user_id, :title, :text)'
		);

		$statement->execute([
			'id' => $post->getId(),
			'user_id' => $post->getUser()->getId(),
			'title' => $post->getTitle(),
			'text' => $post->getText()
		]);
	}

	public function getPostById(UUID $id, UserControllerInterface $userController): Post
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM posts WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);

		$result = $statement->fetch();

		if (!$result) {
			throw new NotFoundException("Post not found: $id");
		}

		return new Post(
			$id,
			$userController->findById(new UUID($result['user_id'])),
			$result['title'],
			$result['text']
		);
	}
}
