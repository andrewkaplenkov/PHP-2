<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'vendor/autoload.php';

use App\Exceptions\AppException;
use App\Exceptions\CommandException;
use App\Models\Blog\Comment;
use App\Models\Blog\Post;
use App\Models\User\Person;
use App\Repositories\UsersRepository\UsersRepository;
use App\Models\UUID\UUID;
use App\Repositories\BlogRepository\CommentsRepository\CommentsRepository;
use App\Repositories\BlogRepository\PostsRepository\PostsRepository;
use App\Repositories\UsersRepository\Utils\Arguments;
use App\Repositories\UsersRepository\Utils\CreateUserCommand;

$faker = Faker\Factory::create();

$pdo = new PDO("sqlite:blog.db", null, null, [
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$usersRepository = new UsersRepository($pdo);
$postsRepository = new PostsRepository($pdo);
$commentsRepository = new CommentsRepository($pdo);

$command = new CreateUserCommand($usersRepository);

try {
	$command->handle(Arguments::parseFromArgv($argv));
} catch (AppException $error) {
	echo $error->getMessage();
}

$user = $usersRepository->findByUserName('admin');
$post = $postsRepository->getPostById(new UUID('71f9477d-e0f4-4773-a32c-7e5ebd47051c'));
$comment = $commentsRepository->getCommentById(new UUID('9e84dca4-96ed-4195-b4a5-6503a45769d5'));

// print_r($comment);

// $commentsRepository->saveComment(new Comment(
// 	UUID::randomUUID(),
// 	$faker->text(10),
// 	$user->getId(),
// 	$post->getId()
// ));

// $postsRepository->savePost(new Post(
// 	UUID::randomUUID(),
// 	$faker->text(10),
// 	$faker->sentence(20),
// 	$user->getId()
// ));
