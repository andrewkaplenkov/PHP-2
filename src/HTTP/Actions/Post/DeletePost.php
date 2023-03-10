<?php

namespace App\HTTP\Actions\Post;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\Post\PostControllerInterface;
use App\Exceptions\HTTPException;
use App\Exceptions\NotFoundException;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\UUID;
use Psr\Log\LoggerInterface;

class DeletePost implements ActionInterface
{
	public function __construct(
		private PostControllerInterface $postController,
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
			$this->postController->deletePost(new UUID($id));
			$this->logger->info("Post deleted " . $id);
		} catch (NotFoundException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'id' => $id,
			'status' => 'deleted'
		]);
	}
}
