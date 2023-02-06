<?php

namespace App\HTTP\Actions\Like;

use App\Controllers\Like\LikeControllerInteface;
use App\Exceptions\HTTPException;
use App\Exceptions\NotFoundException;
use App\HTTP\Actions\ActionInterface;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\UUID;

class DeleteLike implements ActionInterface
{

	public function __construct(
		private LikeControllerInteface $likeController
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$post_id = $request->query('post_id');
			$user_id = $request->query('user_id');
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->likeController->delete(
				new UUID($post_id),
				new UUID($user_id)
			);
		} catch (NotFoundException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'post_id' => $post_id,
			'user_id' => $user_id,
			'status' => 'deleted'
		]);
	}
}
