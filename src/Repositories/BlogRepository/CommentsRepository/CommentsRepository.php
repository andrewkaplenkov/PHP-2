<?php


namespace App\Repositories\BlogRepository\CommentsRepository;

use App\Exceptions\UserNotFoundException;
use App\Models\Blog\Comment;
use App\Models\User\Person;
use App\Models\UUID\UUID;
use PDO;

class CommentsRepository implements CommentsRepositoryInterface
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function saveComment(Comment $comment): void
	{
		$statement = $this->pdo->prepare(
			'INSERT INTO comments(id, text, author_id, post_id) VALUES(:id, :text, :author_id, :post_id)'
		);

		$statement->execute([
			'id' => $comment->getId(),
			'text' => $comment->getText(),
			'author_id' => $comment->getUserId(),
			'post_id' => $comment->getPostId()
		]);
	}

	public function getCommentById(UUID $id): Comment
	{
		$statement = $this->pdo->prepare(
			'SELECT * FROM comments WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);

		$result = $statement->fetch(PDO::FETCH_ASSOC);

		if (!$result) {
			throw new UserNotFoundException("Post not found: $id");
		}

		return new Comment(new UUID($result['id']), $result['text'], new UUID($result['author_id']), new UUID($result['post_id']));
	}
}
