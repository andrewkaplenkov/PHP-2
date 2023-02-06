<?php

use App\Controllers\User\Utils\Arguments;
use App\Controllers\User\Utils\CreateUserCommand;
use App\Exceptions\AppException;


$container = require_once __DIR__ . "/container.php";

$command = $container->get(CreateUserCommand::class);

try {
	$command->handle(Arguments::parseFromArgv($argv));
} catch (AppException $e) {
	echo $e->getMessage();
}
