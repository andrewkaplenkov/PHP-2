<?php


namespace App\Commands;

use App\Controllers\User\UserControllerInterface;
use App\Models\User;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(
        private UserControllerInterface $userController
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('users:create')
            ->setDescription('Creates new user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('first_name', InputArgument::REQUIRED, 'First name')
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create user command started');

        $username = $input->getArgument('username');


        if ($this->userExists($username)) {
            $output->writeln("User already exists: $username");
            return Command::FAILURE;
        }

        $user = User::createFrom(
            $username,
            $input->getArgument('first_name'),
            $input->getArgument('last_name'),
            $input->getArgument('password')
        );

        $this->userController->makeUser($user);

        $output->writeln("User created: $username");
        return Command::SUCCESS;
    }

    private function UserExists(string $username): bool
    {
        try {
            $this->userController->findByUserName($username);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
