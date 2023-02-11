<?php

namespace App\HTTP\Actions\Comment;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\Comment\CommentControllerInterface;
use App\Exceptions\HTTPException;
use App\Exceptions\NotFoundException;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\UUID;
use Psr\Log\LoggerInterface;

class FindCommentById implements ActionInterface
{
	public function __construct(
		private CommentControllerInterface $commentController,
		private LoggerInterface $logger
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$id = $request->query('id');
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$comment = $this->commentController->getCommentById(new UUID($id));
		} catch (NotFoundException $e) {
			$this->logger->warning('Comment not found: ' . $id);
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'id' => (string)$comment->id(),
			'post_id' => (string)$comment->post_id(),
			'user_id' => (string)$comment->user_id(),
			'comment' => $comment->comment()
		]);
	}
}
