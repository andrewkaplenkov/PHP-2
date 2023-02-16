<?php

namespace App\UniTests\Container;


class ClassWithParameter
{
	public function __construct(
		private int $value
	) {
	}

	public function value(): int
	{
		return $this->value;
	}
}
