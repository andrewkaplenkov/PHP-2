<?php

namespace App\Models;

class User
{
	public function __construct(
		private UUID $id,
		private string $userName,
		private string $firstName,
		private string $lastName
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
}
