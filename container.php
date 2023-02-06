<?php

use App\Container\DIContainer;
use App\Controllers\Comment\CommentController;
use App\Controllers\Comment\CommentControllerInterface;
use App\Controllers\Like\LikeController;
use App\Controllers\Like\LikeControllerInteface;
use App\Controllers\Post\PostController;
use App\Controllers\Post\PostControllerInterface;
use App\Controllers\User\UserController;
use App\Controllers\User\UserControllerInterface;

require_once __DIR__ . '/vendor/autoload.php';


$container = new DIContainer();

$container->bind(
	PDO::class,
	new PDO("sqlite:blog.db", null, null, [
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	])
);

$container->bind(
	UserControllerInterface::class,
	UserController::class
);

$container->bind(
	PostControllerInterface::class,
	PostController::class
);

$container->bind(
	CommentControllerInterface::class,
	CommentController::class
);

$container->bind(
	LikeControllerInteface::class,
	LikeController::class
);

return $container;
