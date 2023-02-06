<?php


use App\Exceptions\AppException;
use App\Exceptions\HTTPException;
use App\HTTP\Actions\Comment\CreateNewComment;
use App\HTTP\Actions\Comment\DeleteComment;
use App\HTTP\Actions\Comment\FindCommentById;
use App\HTTP\Actions\Like\DeleteLike;
use App\HTTP\Actions\Like\GetLikes;
use App\HTTP\Actions\Like\SaveLike;
use App\HTTP\Actions\Post\CreateNewPost;
use App\HTTP\Actions\Post\DeletePost;
use App\HTTP\Actions\Post\FindPostById;
use App\HTTP\Actions\User\CreateNewUser;
use App\HTTP\Actions\User\DeleteUser;
use App\HTTP\Actions\User\FindByUsername;
use App\HTTP\Request\Request;

use App\HTTP\Response\UnsuccessfullResponse;

$container = require_once __DIR__ . '/container.php';

$request = new Request(
	$_GET,
	$_SERVER,
	file_get_contents('php://input')
);

try {
	$path = $request->path();
	$method = $request->method();
} catch (HTTPException $e) {
	(new UnsuccessfullResponse($e->getMessage()))->send();
}

$routes = [
	'GET' => [
		'/users/show' => FindByUsername::class,
		'/posts/show' => FindPostById::class,
		'/comments/show' => FindCommentById::class,
		'/likes/show' => GetLikes::class
	],
	'POST' => [
		'/users/new' => CreateNewUser::class,
		'/posts/new' => CreateNewPost::class,
		'/comments/new' => CreateNewComment::class,
		'/likes/new' => SaveLike::class
	],
	'DELETE' => [
		'/users/delete' => DeleteUser::class,
		'/posts/delete' => DeletePost::class,
		'/comments/delete' => DeleteComment::class,
		'/likes/delete' => DeleteLike::class
	]
];

if (!array_key_exists($method, $routes)) {
	(new UnsuccessfullResponse("Method Error"))->send();
	return;
}

if (!array_key_exists($path, $routes[$method])) {
	(new UnsuccessfullResponse("Path not found"))->send();
	return;
}

$actionClassname = $routes[$method][$path];

$action = $container->get($actionClassname);

try {
	$response = $action->handle($request);
} catch (AppException $e) {
	(new UnsuccessfullResponse($e->getMessage()))->send();
}

$response->send();
