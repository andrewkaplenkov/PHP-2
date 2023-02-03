<?php

namespace App\HTTP\Actions\Post;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\Post\PostControllerInterface;
use App\Exceptions\HTTPException;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\Post;
use App\Models\UUID;

class CreateNewPost implements ActionInterface
{
	public function __construct(
		private PostControllerInterface $postController
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$post = new Post(
				UUID::random(),
				new UUID($request->JsonBodyField('user_id')),
				$request->JsonBodyField('title'),
				$request->JsonBodyField('text'),
			);
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->postController->makePost($post);
			return new SuccessfullResponse([
				'id' => (string)$post->id(),
				'status' => 'created'
			]);
		} catch (HTTPException) {
			return new UnsuccessfullResponse("Post not created!");
		}
	}
}
