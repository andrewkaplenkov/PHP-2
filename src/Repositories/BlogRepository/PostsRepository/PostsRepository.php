<?php

namespace App\Repositories\BlogRepository\PostsRepository;

use App\Exceptions\UserNotFoundException;
use App\Models\Blog\Post;
use App\Models\User\Person;
use App\Models\UUID\UUID;
use PDO;

class PostsRepository implements PostsRepositoryInterface
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function savePost(Post $post): void
	{
		$statement = $this->pdo->prepare(
			'INSERT INTO posts(id, title, text, author_id) VALUES(:id, :title, :text, :author_id)'
		);

		$statement->execute([
			'id' => $post->getId(),
			'title' => $post->getTitle(),
			'text' => $post->getDescription(),
			'author_id' => $post->getUserId()
		]);
	}

	public function getPostById(UUID $id): Post
	{
		$statement = $this->pdo->prepare(
			'SELECT * FROM posts WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);

		$result = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$result) {
			throw new UserNotFoundException("Post not found: $id");
		}

		return new Post(new UUID($result['id']), $result['title'], $result['text'], new UUID($result['author_id']));
	}

	// public function getPostByTitle(string $title): Post
	// {
	// 	$statement = $this->pdo->prepare(
	// 		'SELECT * FROM posts WHERE title = :title'
	// 	);

	// 	$statement->execute([
	// 		'title' => $title
	// 	]);

	// 	$result = $statement->fetch(PDO::FETCH_ASSOC);

	// 	if (!$result) {
	// 		throw new UserNotFoundException("Post not found: $title");
	// 	}

	// 	return new Post(new UUID($result['id']), $result['title'], $result['text'], new UUID($result['author_id']));
	// }
}
