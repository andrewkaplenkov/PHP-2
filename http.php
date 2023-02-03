<?php

use App\Controllers\Comment\CommentController;
use App\Controllers\Post\PostController;
use App\Controllers\User\UserController;
use App\Exceptions\AppException;
use App\Exceptions\HTTPException;
use App\HTTP\Actions\Comment\CreateNewComment;
use App\HTTP\Actions\Comment\DeleteComment;
use App\HTTP\Actions\Comment\FindCommentById;
use App\HTTP\Actions\Post\CreateNewPost;
use App\HTTP\Actions\Post\DeletePost;
use App\HTTP\Actions\Post\FindPostById;
use App\HTTP\Actions\User\CreateNewUser;
use App\HTTP\Actions\User\DeleteUser;
use App\HTTP\Actions\User\FindByUsername;
use App\HTTP\Request\Request;
use App\HTTP\Response\SuccessfullResponse;
use App\HTTP\Response\UnsuccessfullResponse;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO("sqlite:blog.db", null, null, [
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

$routes = [
	'GET' => [
		'/users/show' => new FindByUsername(new UserController($connection)),
		'/posts/show' => new FindPostById(new PostController($connection)),
		'/comments/show' => new FindCommentById(new CommentController($connection))
	],
	'POST' => [
		'/users/new' => new CreateNewUser(new UserController($connection)),
		'/posts/new' => new CreateNewPost(new PostController($connection)),
		'/comments/new' => new CreateNewComment(new CommentController($connection))
	],
	'DELETE' => [
		'/users/delete' => new DeleteUser(new UserController($connection)),
		'/posts/delete' => new DeletePost(new PostController($connection)),
		'/comments/delete' => new DeleteComment(new CommentController($connection))
	]
];

try {
	$path = $request->path();
	$method = $request->method();
} catch (HTTPException $e) {
	(new UnsuccessfullResponse($e->getMessage()))->send();
}

if (!array_key_exists($method, $routes)) {
	(new UnsuccessfullResponse("Method Error"))->send();
	return;
}

if (!array_key_exists($path, $routes[$method])) {
	(new UnsuccessfullResponse("Path not found"))->send();
	return;
}

$action = $routes[$method][$path];

try {
	$response = $action->handle($request);
} catch (AppException $e) {
	(new UnsuccessfullResponse($e->getMessage()))->send();
}

$response->send();
