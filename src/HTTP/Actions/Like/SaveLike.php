<?php

namespace App\HTTP\Actions\Like;

use App\Controllers\Like\LikeControllerInteface;
use App\Exceptions\AlreadyExistsException;
use App\Exceptions\AppException;
use App\Exceptions\HTTPException;
use App\HTTP\Actions\ActionInterface;
use App\HTTP\Auth\TokenAuthInterface;
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
		private LikeControllerInteface $likeController,
		private TokenAuthInterface $indentification

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
			$post_id = new UUID($request->JsonBodyField('post_id'));
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->likeController->checkIfExists($post_id, $user->id());
		} catch (AlreadyExistsException) {
			return new UnsuccessfullResponse("Already liked by user: " . (string)$user->id());
		}

		$like = new Like(
			UUID::random(),
			$post_id,
			$user->id()
		);

		try {
			$this->likeController->save($like);
			return new SuccessfullResponse([
				'post_id' => (string)$post_id,
				'status' => "one like added from user " . (string)$user->id()
			]);
		} catch (Exception $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}
	}
}
