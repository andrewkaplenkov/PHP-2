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

class FindPostById implements ActionInterface
{
	public function __construct(private PostControllerInterface $postController)
	{
	}

	public function handle(Request $request): Response
	{
		try {
			$id = $request->query('id');
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$post  = $this->postController->getPostById(new UUID($id));
		} catch (NotFoundException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'id' => (string)$post->id(),
			'user_id' => (string)$post->user_id(),
			'title' => $post->title(),
			'text' => $post->text()
		]);
	}
}
