<?php

namespace App\Controllers\Post;

use App\Exceptions\NotFoundException;
use App\Models\Post;
use App\Models\UUID;
use PDO;

class PostController implements PostControllerInterface
{

	public function __construct(private PDO $connection)
	{
	}

	public function makePost(Post $post): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO posts(id, user_id, title, text) VALUES(:id, :user_id, :title, :text)'
		);

		$statement->execute([
			'id' => $post->id(),
			'user_id' => $post->user_id(),
			'title' => $post->title(),
			'text' => $post->text()
		]);
	}

	public function getPostById(UUID $id): Post
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
			new UUID($result['user_id']),
			$result['title'],
			$result['text']
		);
	}

	public function deletePost(UUID $id): void
	{
		$statement = $this->connection->prepare(
			'DELETE FROM posts WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);
	}
}
