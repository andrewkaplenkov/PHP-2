<?php

namespace App\User;

class User
{
	private int $id;
	private string $fullName;

	public function __construct(int $id, Object $faker)
	{
		$this->id = $id;
		$this->fullName = $faker->name();
	}

	public function __toString()
	{
		return $this->fullName;
	}
}
