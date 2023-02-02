<?php

use App\Controllers\Comment\CommentController;
use App\Controllers\Post\PostController;
use App\Controllers\User\UserController;
use App\Controllers\User\Utils\Arguments;
use App\Controllers\User\Utils\CreateUserCommand;
use App\Exceptions\AlreadyExistsException;
use App\Exceptions\AppException;
use App\Exceptions\NotFoundException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\UUID;

require_once __DIR__ . '/vendor/autoload.php';


$faker = Faker\Factory::create();

$connection = new PDO("sqlite:blog.db", null, null, [
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$userController = new UserController($connection);
$postController = new PostController($connection);
$commentController = new CommentController($connection);

$command = new CreateUserCommand($userController);

$user = $userController->findByUserName('kotletka');
$post = $postController->getPostById(new UUID('9b43495f-7f31-4ed5-9b8c-94dbbf9a5ae7'), $userController);
$comment = $commentController->getCommentById(new UUID('d74cf51b-f782-4fd1-8f9b-d66114cabb31'), $postController, $userController);

try {
	$command->handle(Arguments::parseFromArgv($argv));
	// $postController->makePost($post);
	// $commentController->makeComment($comment);
} catch (AppException $e) {
	echo $e->getMessage();
}
