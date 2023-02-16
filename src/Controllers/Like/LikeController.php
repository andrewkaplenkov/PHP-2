<?php

namespace App\Controllers\Like;

use App\Exceptions\AlreadyExistsException;
use App\Models\Like;
use App\Models\UUID;
use PDO;
use PDOStatement;

class LikeController implements LikeControllerInteface
{
	public function __construct(
		private PDO $connection
	) {
	}

	public function checkIfExists(UUID $post_id, UUID $user_id): void
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id'
		);

		$statement->execute([
			'post_id' => (string)$post_id,
			'user_id' => (string)$user_id
		]);

		$result = $statement->fetchAll();

		if ($result) {
			throw new AlreadyExistsException("Liked already");
		}
	}

	public function save(Like $like): void
	{

		$statement = $this->connection->prepare(
			'INSERT INTO likes(id, post_id, user_id) VALUES (:id, :post_id, :user_id)'
		);

		$statement->execute([
			'id' => $like->id(),
			'post_id' => $like->post_id(),
			'user_id' => $like->user_id()
		]);
	}

	public function getByPostId(UUID $post_id): array
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM likes WHERE post_id = :post_id'
		);

		$statement->execute([
			'post_id' => (string)new UUID($post_id)
		]);

		return $statement->fetchAll();
	}

	public function delete(UUID $post_id, UUID $user_id): void
	{
		$statement = $this->connection->prepare(
			'DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id'
		);

		$statement->execute([
			'post_id' => (string)$post_id,
			'user_id' => (string)$user_id
		]);
	}
}
