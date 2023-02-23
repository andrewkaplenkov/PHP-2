<?php

namespace App\Commands;

use App\Controllers\Post\PostControllerInterface;
use App\Models\UUID;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{
    public function __construct(
        private PostControllerInterface $postController
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('posts:delete')
            ->setDescription('Delete post')
            ->addArgument('id', InputArgument::REQUIRED, 'post ID')
            ->addOption(
                'check-existence',
                'c',
                InputOption::VALUE_NONE,
                'Check if post actually exists'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Delete post command started');

        $question = new ConfirmationQuestion(
            'Delete post [Y/n]? ',
            false
        );

        if (!$this->getHelper('question')->ask($input, $output, $question)) {
            return Command::SUCCESS;
        }

        $id = $input->getArgument('id');

        if ($input->getOption('check-existence')) {
            try {
                $this->postController->getPostById(new UUID($id));
            } catch (Exception $e) {
                $output->writeln($e->getMessage());
                return Command::FAILURE;
            }
        }

        $this->postController->deletePost(new UUID($id));

        $output->writeln("Post deleted: $id");
        return Command::SUCCESS;
    }
}
