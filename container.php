<?php

use App\Container\DIContainer;
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\AuthControllerInterface;
use App\Controllers\Comment\CommentController;
use App\Controllers\Comment\CommentControllerInterface;
use App\Controllers\Like\LikeController;
use App\Controllers\Like\LikeControllerInteface;
use App\Controllers\Post\PostController;
use App\Controllers\Post\PostControllerInterface;
use App\Controllers\User\UserController;
use App\Controllers\User\UserControllerInterface;
use App\HTTP\Auth\AuthInterface;
use App\HTTP\Auth\BearerTokenAuth;
use App\HTTP\Auth\PasswordAuthInterface;
use App\HTTP\Auth\PasswordIdentification;
use App\HTTP\Auth\TokenAuthInteface;
use App\HTTP\Auth\TokenAuthInterface;
use Dotenv\Dotenv;
use Faker\Provider\ru_RU\Internet;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\ru_RU\Text;
use Faker\Provider\Lorem;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$container->bind(
	PDO::class,
	new PDO("sqlite:" . $_SERVER['SQLITE_DB_PATH'], null, null, [
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	])
);

$logger = new Logger('blog');


if ($_SERVER['LOG_TO_FILES'] === 'yes') {
	$logger
		->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log'))
		->pushHandler(new StreamHandler(
			__DIR__ . '/logs/blog.error.log',
			level: Logger::ERROR,
			bubble: false
		));
}

if ($_SERVER['LOG_TO_CONSOLE'] === 'yes') {
	$logger->pushHandler(new StreamHandler('php://stdout'));
}

$container->bind(
	LoggerInterface::class,
	$logger
);


$faker = new \Faker\Generator();

$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));

$container->bind(
	\Faker\Generator::class,
	$faker
);


$container->bind(
	PasswordAuthInterface::class,
	PasswordIdentification::class
);

$container->bind(
	TokenAuthInterface::class,
	BearerTokenAuth::class
);

$container->bind(
	AuthControllerInterface::class,
	AuthController::class
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
