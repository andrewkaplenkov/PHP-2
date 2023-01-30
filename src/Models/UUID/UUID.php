<?php

namespace App\Models\UUID;

use App\Excepetions\InvalidArgumentException;
use InvalidArgumentException as GlobalInvalidArgumentException;

class UUID
{
	private string $id;

	public function __construct(string $id)
	{

		if (!uuid_is_valid($id)) {
			throw new GlobalInvalidArgumentException("Malformed UUID: $id");
		}

		$this->id = $id;
	}

	public static function randomUUID(): self
	{
		return new self(uuid_create(UUID_TYPE_RANDOM));
	}

	public function __toString()
	{
		return $this->id;
	}
}
