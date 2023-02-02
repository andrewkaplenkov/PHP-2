<?php

namespace App\Models;

class User
{
	// private UUID $id;
	// private string $userName;
	// private string $firstName;
	// private string $lastName;

	public function __construct(
		private UUID $id,
		private string $userName,
		private string $firstName,
		private string $lastName
	) {
		// $this->id = $id;
		// $this->userName = $userName;
		// $this->firstName = $firstName;
		// $this->lastName = $lastName;
	}

	public function getId(): UUID
	{
		return $this->id;
	}

	public function getUserName(): string
	{
		return $this->userName;
	}
	public function getFirstName(): string
	{
		return $this->firstName;
	}
	public function getLastName(): string
	{
		return $this->lastName;
	}
}
