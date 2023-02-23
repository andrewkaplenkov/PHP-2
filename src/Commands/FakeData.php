<?php

namespace App\Commands;

use App\Controllers\Comment\CommentControllerInterface;
use App\Controllers\Post\PostControllerInterface;
use App\Controllers\User\UserControllerInterface;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Models\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FakeData extends Command
{
    public function __construct(
        private \Faker\Generator $faker,
        private UserControllerInterface $userController,
        private PostControllerInterface $postController,
        private CommentControllerInterface $commentController
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Number of users to make'
            )
            ->addOption(
                'posts-number',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Number of posts to make'
            );
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
            $this->faker->userName,
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->password,

        );
        $this->userController->makeUser($user);
        return $user;
    }

    private function createFakePost(User $user): Post
    {
        $post = new Post(
            UUID::random(),
            $user->id(),
            $this->faker->sentence(6, true),
            $this->faker->realText
        );
        $this->postController->makePost($post);
        return $post;
    }

    private function createFakeComment(Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post->id(),
            $post->user_id(),
            $this->faker->sentence(15, true)
        );
        $this->commentController->makeComment($comment);
        return $comment;
    }



    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {


        $usersNumber = (int)$input->getOption('users-number');
        $postsNumber = (int)$input->getOption('posts-number');

        $users = [];
        for ($i = 0; $i < $usersNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->username());
        }

        $posts = [];
        foreach ($users as $user) {
            for ($i = 0; $i < $postsNumber; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->title());
            }
        }

        foreach ($posts as $post) {
            for ($i = 0; $i < 3; $i++) {
                $comment = $this->createFakeComment($post);
                $output->writeln('Comment created: ' . $comment->comment());
            }
        }

        return Command::SUCCESS;
    }
}
