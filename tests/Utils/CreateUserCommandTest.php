<?php

namespace App\Tests\Utils;

// use App\Controllers\User\DummyUserController;

use App\Commands\CreateUser;
use App\Controllers\User\UserControllerInterface;
use App\Controllers\User\Utils\Arguments;
use App\Controllers\User\Utils\CreateUserCommand;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\AppException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InvalidArgumentException;

use App\Models\User;
use App\Models\UUID;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateUserCommandTest extends TestCase
{

    public function makeDummyUserController(): UserControllerInterface
    {
        return new class implements UserControllerInterface
        {

            public function makeUser(User $user): void
            {
                throw new AlreadyExistsException("User already exists: Ivan");
            }

            public function findById(UUID $id): User
            {
                throw new NotFoundException("User not found: $id");
            }

            public function findByUserName(string $userName): User
            {
                throw new NotFoundException("User not found: $userName");
            }

            public function fetchUser(PDOStatement $statement, string $searchQuery): User
            {
                throw new NotFoundException("User not found");
            }

            public function deleteUser(string $username): void
            {
            }
        };
    }

    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $command = new CreateUser($this->makeDummyUserController());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("User already exists: Ivan");
        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'first_name' => 'Ivan',
                'last_name' => 'Ivan',
                'password' => 'qwerty'
            ]),
            new NullOutput()
        );
    }

    // public function testItRequiresFirstName(): void
    // {
    //     $command = new CreateUserCommand($this->makeDummyUserController());

    //     $this->expectException(InvalidArgumentException::class);
    //     $this->expectExceptionMessage("Empty value for: firstName");

    //     $command->handle(new Arguments(['userName' => 'Ivan']));
    // }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUser($this->makeDummyUserController());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Not enough arguments (missing: 'last_name').");

        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'first_name' => 'Ella',
                'password' => 'qwerty'
            ]),
            new NullOutput()
        );
    }

    public function testItSavesUserToDatabase(): void
    {

        $userController = new class implements UserControllerInterface
        {
            private bool $called;

            public function makeUser(User $user): void
            {
                $this->called = true;
            }

            public function findById(UUID $id): User
            {
                throw new NotFoundException("User not found");
            }

            public function findByUserName(string $userName): User
            {
                throw new NotFoundException("User not found");
            }

            public function fetchUser(PDOStatement $statement, string $searchQuery): User
            {
                throw new NotFoundException("User not found");
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $command = new CreateUser($userController);

        $command->run(
            new ArrayInput([
                'username' => 'Ivan',
                'first_name' => 'Ivan',
                'last_name' => 'Ivan',
                'password' => 'qwerty'
            ]),
            new NullOutput()
        );

        $this->assertTrue($userController->wasCalled());
    }
}
