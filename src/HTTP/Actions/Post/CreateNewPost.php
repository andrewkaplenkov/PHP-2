<?php

namespace App\HTTP\Actions\Post;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\Post\PostControllerInterface;
use App\Exceptions\HTTPException;
use App\HTTP\Auth\TokenAuthInterface;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\Post;
use App\Models\UUID;
use Exception;
use Psr\Log\LoggerInterface;

class CreateNewPost implements ActionInterface
{
	public function __construct(
		private PostControllerInterface $postController,
		private TokenAuthInterface $indentification,
		private LoggerInterface $logger
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$user = $this->indentification->user($request);
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}


		try {
			$post = new Post(
				UUID::random(),
				$user->id(),
				$request->JsonBodyField('title'),
				$request->JsonBodyField('text'),
			);
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->postController->makePost($post);
			$this->logger->info("Post created " . $post->id());
			return new SuccessfullResponse([
				'id' => (string)$post->id(),
				'status' => 'created'
			]);
		} catch (HTTPException) {
			return new UnsuccessfullResponse("Post not created!");
		}
	}
}
