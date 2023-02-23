<?php

namespace App\Models;

class User
{
	public function __construct(
		private UUID $id,
		private string $userName,
		private string $firstName,
		private string $lastName,
		private string $passwordHash
	) {
	}

	public function id(): UUID
	{
		return $this->id;
	}

	public function username(): string
	{
		return $this->userName;
	}
	public function firstName(): string
	{
		return $this->firstName;
	}
	public function lastName(): string
	{
		return $this->lastName;
	}

	public function passwordHash(): string {
		return $this->passwordHash;
	}

	private static function hash(string $password, UUID $id): string {
		return hash('sha256', $password . $id);
	}

	public function passwordCheck(string $password): bool {
		return $this->passwordHash === self::hash($password, $this->id);
	}

	public static function createFrom(
		string $username,
		string $firstName,
		string $lastName,
		string $password,
	): self
	{
		return new self(
			$id = UUID::random(),
			$username,
			$firstName,
			$lastName,
			self::hash($password, $id)
		);
	}
}
