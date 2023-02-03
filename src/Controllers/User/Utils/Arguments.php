<?php


namespace App\Controllers\User\Utils;

use App\Exceptions\InvalidArgumentException;

// final class Arguments
// {
// 	private iterable $arguments = [];

// 	public function __construct(iterable $arguments)
// 	{
// 		foreach ($arguments as $key => $value) {
// 			$stringValue = trim((string)$value);
// 			if (empty($stringValue)) {
// 				continue;
// 			}
// 			$this->arguments[(string)$key] = $stringValue;
// 		}
// 	}

// 	public static function parseFromArgv(iterable $argv): self
// 	{
// 		$arguments = [];

// 		foreach ($argv as $argument) {
// 			$parts = explode('=', $argument);
// 			if (count($parts) !== 2) {
// 				continue;
// 			}
// 			$arguments[$parts[0]] = $parts[1];
// 		}

// 		return new self($arguments);
// 	}

// 	public function getArgumentValue(string $key): string
// 	{
// 		if (!array_key_exists($key, (array)$this->arguments)) {
// 			throw new InvalidArgumentException("Empty value for: $key");
// 		}
// 		return $this->arguments[$key];
// 	}
// }
