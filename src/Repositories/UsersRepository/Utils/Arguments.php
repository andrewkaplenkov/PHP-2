<?php


namespace App\Repositories\UsersRepository\Utils;

use App\Exceptions\CommandException;

final class Arguments
{
	private iterable $arguments = [];

	public function __construct(iterable $arguments)
	{

		foreach ($arguments as $key => $value) {
			$stringValue = trim((string)$value);

			if (empty($stringValue)) {
				continue;
			}

			$this->arguments[(string)$key] = $stringValue;
		}
	}

	public static function parseFromArgv(array $argv): self
	{
		$arguments = [];

		foreach ($argv as $argument) {
			$parts = explode('=', $argument);
			if (count($parts) !== 2) {
				continue;
			}
			$arguments[$parts[0]] = $parts[1];
		}

		return new self($arguments);
	}

	public function getArgument(string $argument): string
	{
		if (!array_key_exists($argument, (array)$this->arguments)) {
			throw new CommandException("Argument not found: $argument");
		}

		return $this->arguments[$argument];
	}
}
