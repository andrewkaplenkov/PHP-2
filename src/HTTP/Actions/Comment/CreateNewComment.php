<?php

namespace App\HTTP\Actions\Comment;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\Comment\CommentControllerInterface;
use App\Exceptions\HTTPException;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\Comment;
use App\Models\UUID;

class CreateNewComment implements ActionInterface
{
	public function __construct(
		private CommentControllerInterface $commentController
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$comment = new Comment(
				UUID::random(),
				new UUID($request->JsonBodyField('post_id')),
				new UUID($request->JsonBodyField('user_id')),
				$request->JsonBodyField('comment')
			);
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->commentController->makeComment($comment);
			return new SuccessfullResponse([
				'id' => (string)$comment->id(),
				'status' => 'created'
			]);
		} catch (HTTPException) {
			return new UnsuccessfullResponse("Post not created!");
		}
	}
}
