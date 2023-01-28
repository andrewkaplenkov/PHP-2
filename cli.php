<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once 'vendor/autoload.php';
$faker = Faker\Factory::create();

use App\User\User;
use App\Post\Post;
use App\Comment\Comment;

$user = new User(1, $faker);
$post = new Post(1, $faker, $user);
$comment = new Comment(1, $faker, $user, $post);

if (isset($argv[1]) && ($argv[1] === 'user')) {
	echo $user;
} else if (isset($argv[1]) && ($argv[1] === 'post')) {
	echo $post;
} else if (isset($argv[1]) && ($argv[1] === 'comment')) {
	echo $comment;
} else {
	echo "User: $user \n Post: $post \n Comments: $comment";
}
