<?php

use App\Commands\CreateUser;
use App\Commands\DeletePost;
use App\Commands\FakeData;
use App\Commands\UpdateUser;
use App\Exceptions\AppException;
use Symfony\Component\Console\Application;

$container = require_once __DIR__ . "/container.php";

$application = new Application();

$commandClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    FakeData::class
];

foreach ($commandClasses as $commandClass) {
    $command = $container->get($commandClass);
    $application->add($command);
}

$application->run();
