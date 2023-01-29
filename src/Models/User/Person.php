<?php

namespace App\Models\User;

use App\Models\UUID\UUID;

class Person
{
	private UUID $id;
	private string $userName;
	private string $firstName;
	private string $lastName;

	public function __construct(UUID $id, string $userName, string $firstName, string $lastName)
	{
		$this->id = $id;
		$this->userName = $userName;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
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
