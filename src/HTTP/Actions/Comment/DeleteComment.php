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

class DeleteComment implements ActionInterface
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
			$this->commentController->deleteComment(new UUID($id));
			$this->logger->info("Comment deleted " . $id);
		} catch (NotFoundException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'id' => (string)$id,
			'status' => 'deleted'
		]);
	}
}
