<?php

namespace App\Repositories\UsersRepository;

use App\Models\User\Person;
use App\Models\UUID\UUID;
use PDOStatement;

interface UsersRepositoryInterface
{
	public function makeUser(Person $person): void;

	public function findById(UUID $id): Person;

	public function findByUserName(string $userName): Person;

	public function getUser(PDOStatement $statement, string $searchQuery): ?Person;
}
