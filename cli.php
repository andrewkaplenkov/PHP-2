<?php

require_once __DIR__ . '/vendor/autoload.php';
// require_once 'vendor/autoload.php';

use App\Exceptions\AppException;
use App\Exceptions\CommandException;
use App\Models\User\Person;
use App\Repositories\UsersRepository\UsersRepository;
use App\Models\UUID\UUID;
use App\Repositories\UsersRepository\Utils\Arguments;
use App\Repositories\UsersRepository\Utils\CreateUserCommand;

// $faker = Faker\Factory::create();

$pdo = new PDO("sqlite:blog.db", null, null, [
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$repository = new UsersRepository($pdo);
$command = new CreateUserCommand($repository);

try {
	$command->handle(Arguments::parseFromArgv($argv));
} catch (AppException $error) {
	echo $error->getMessage();
}
