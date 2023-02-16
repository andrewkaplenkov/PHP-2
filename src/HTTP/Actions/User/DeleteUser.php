<?php

namespace App\HTTP\Actions\User;

use App\HTTP\Actions\ActionInterface;
use App\Controllers\User\UserControllerInterface;
use App\Exceptions\HTTPException;
use App\Exceptions\NotFoundException;
use App\HTTP\Request\Request;
use App\HTTP\Response\Response;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;
use App\Models\UUID;
use Psr\Log\LoggerInterface;

class DeleteUser implements ActionInterface
{
	public function __construct(
		private UserControllerInterface $userController,
		private LoggerInterface $logger
	) {
	}

	public function handle(Request $request): Response
	{
		try {
			$username = $request->query('username');
		} catch (HTTPException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		try {
			$this->userController->deleteUser($username);
			$this->logger->info("User deleted " . $username);
		} catch (NotFoundException $e) {
			return new UnsuccessfullResponse($e->getMessage());
		}

		return new SuccessfullResponse([
			'username' => $username,
			'status' => 'deleted'
		]);
	}
}
