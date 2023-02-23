<?php


namespace App\Controllers\User;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Models\UUID;
use PDO;
use PDOStatement;

class UserController implements UserControllerInterface
{
	public function __construct(private PDO $connection)
	{
	}

	public function makeUser(User $user): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO users (id, userName, firstName, lastName, password) 
			VALUES (:id, :userName, :firstName, :lastName, :password)'
		);

		$statement->execute([
			'id' => $user->id(),
			'userName' => $user->username(),
			'firstName' => $user->firstName(),
			'lastName' => $user->lastName(),
			'password' => $user->passwordHash()
		]);
	}

	public function findById(UUID $id): User
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM users WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);

		return $this->fetchUser($statement, (string)$id);
	}

	public function findByUserName(string $userName): User
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM users WHERE userName = :userName'
		);

		$statement->execute([
			'userName' => $userName
		]);

		return $this->fetchUser($statement, $userName);
	}

	public function fetchUser(PDOStatement  $statement, string $searchQuery): User
	{
		$result = $statement->fetch();

		if ($result === false) {
			throw new NotFoundException("User not found: $searchQuery");
		}

		return new User(
			new UUID($result['id']),
			$result['userName'],
			$result['firstName'],
			$result['lastName'],
			$result['password']
		);
	}

	public function deleteUser(string $userName): void
	{
		$statement = $this->connection->prepare(
			'DELETE FROM users WHERE userName = :userName'
		);

		$statement->execute([
			'userName' => $userName
		]);
	}
}
