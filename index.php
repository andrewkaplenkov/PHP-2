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

// $user = $userController->findByUserName('admin');
// $post = $postController->getPostById(new UUID('e772e994-e23b-43a7-bd55-ca9648f82b03'));
// $comment = $commentController->getCommentById(new UUID('a68d34b1-89b7-413d-b4d1-c0576f99ee13'));

try {
	// $command->handle(Arguments::parseFromArgv($argv));
	// $postController->makePost($post);
	// $commentController->makeComment($comment);
} catch (AppException $e) {
	echo $e->getMessage();
}
