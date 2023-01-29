<?php

namespace App\Repositories\UsersRepository;

use App\Models\User\Person;
use PDO;
use App\Exceptions\UserNotFoundException;
use App\Models\UUID\UUID;
use PDOStatement;

class UsersRepository implements UsersRepositoryInterface
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function makeUser(Person $person): void
	{
		$statement = $this->pdo->prepare(
			'INSERT INTO users (id, user_name, first_name, last_name) VALUES (:id, :user_name, :first_name, :last_name)'
		);

		$statement->execute([
			'id' => (string)$person->getId(),
			'user_name' => $person->getUserName(),
			'first_name' => $person->getFirstName(),
			'last_name' => $person->getLastName()
		]);
	}

	public function findById(UUID $id): Person
	{
		$statement = $this->pdo->prepare(
			'SELECT * FROM users WHERE id = :id'
		);

		$statement->execute([
			'id' => (string)$id
		]);

		return $this->getUser($statement, $id);
	}

	public function findByUserName(string $userName): Person
	{
		$statement = $this->pdo->prepare(
			'SELECT * FROM users WHERE user_name = :userName'
		);

		$statement->execute([
			'userName' => $userName
		]);

		return $this->getUser($statement, $userName);
	}

	public function getUser(PDOStatement $statement, string $searchQuery): ?Person
	{
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		if ($result === false) {
			throw new UserNotFoundException("User not found: $searchQuery:");
		}

		return new Person(new UUID($result['id']), $result['user_name'], $result['first_name'], $result['last_name']);
	}
}
