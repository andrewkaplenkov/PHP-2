<?php

namespace App\Models;

use App\Exceptions\InvalidArgumentException;

class UUID
{
	public function __construct(private string $id)
	{
		$id = trim($id);
		if (!uuid_is_valid($id)) {
			throw new InvalidArgumentException("Invalid UUID format: $id");
		}

		$this->id = $id;
	}

	public static function random(): self
	{
		return new self(uuid_create(UUID_TYPE_RANDOM));
	}

	public function __toString()
	{
		return $this->id;
	}
}
