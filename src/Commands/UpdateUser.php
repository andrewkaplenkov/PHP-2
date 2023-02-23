<?php

namespace App\Commands;

use App\Controllers\User\UserControllerInterface;
use App\Models\User;
use App\Models\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
{
    public function __construct(
        private UserControllerInterface $userController
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('users:update')
            ->setDescription('Update user info')
            ->addArgument('id', InputArgument::REQUIRED, 'User id')
            ->addOption(
                'first-name',
                'f',
                InputOption::VALUE_OPTIONAL,
                'First name'
            )
            ->addOption(
                'last-name',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Last name'
            );
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Update user info command started');

        $id = $input->getArgument('id');
        $user = $this->userController->findById(new UUID($id));

        $firstName = $input->getOption('first-name');
        $lastName = $input->getOption('last-name');


        if (empty($firstName) && empty($lastName)) {
            $output->writeln('Nothing to update');
            return Command::SUCCESS;
        }

        $firstName = empty($firstName) ? $user->firstName() : $firstName;
        $lastName = empty($lastName) ? $user->lastName() : $lastName;


        $updatedUser = new User(
            $user->id(),
            $user->username(),
            $firstName,
            $lastName,
            $user->passwordHash()

        );

        $this->userController->makeUser($updatedUser);

        $output->writeln("User updated: " . $updatedUser->username());
        return Command::SUCCESS;
    }
}
