<?php

namespace App\HTTP\Actions\Like;

use App\Controllers\Like\LikeControllerInteface;
use App\Exceptions\AppException;
use App\Exceptions\HTTPException;
use App\HTTP\Actions\ActionInterface;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\Like;
use App\Models\UUID;
use Exception;

class SaveLike implements ActionInterface
{
	public function __construct(
		private LikeControllerInteface $likeController
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$post_id = new UUID($request->query('post_id'));
			$user_id = new UUID($request->query('user_id'));
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}


		try {
			$this->likeController->save($post_id, $user_id);
			return new SuccessfullResponse([
				'id' => (string)$post_id,
				'status' => "one like added from user " . (string)$user_id
			]);
		} catch (Exception) {
			return new UnsuccessfullResponse("Already liked by user: " . $user_id);
		}
	}
}
