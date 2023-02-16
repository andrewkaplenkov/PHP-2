<?php

namespace App\Controllers\User;

use App\Models\User;
use App\Models\UUID;
use PDOStatement;

interface UserControllerInterface
{
	public function makeUser(User $user): void;

	public function findById(UUID $id): User;

	public function findByUserName(string $userName): User;

	public function fetchUser(PDOStatement $statement, string $searchQuery): User;

	public function deleteUser(string $userName): void;
}
