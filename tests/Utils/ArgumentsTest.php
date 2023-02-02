<?php

namespace App\Tests\Utils;

use App\Controllers\User\Utils\Arguments;
use App\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase
{

	public function testItReturnsArgumentValueByName(): void
	{
		$arguments = new Arguments(['someKey' => 'someValue']);

		$value = $arguments->getArgumentValue('someKey');

		$this->assertEquals('someValue', $value);
	}

	public function testItThrowsAnExceptionWhenArgumentIsAbsent(): void
	{
		$arguments = new Arguments([]);

		$this->expectException(InvalidArgumentException::class);

		$this->expectExceptionMessage("Empty value for: someKey");

		$arguments->getArgumentValue('someKey');
	}

	public function argumentsProvider(): iterable
	{
		return [
			['some_string', 'some_string'],
			[' some_string', 'some_string'],
			[' some_string ', 'some_string'],
			[123, '123'],
			[12.3, '12.3'],
		];
	}

	/**
	 * @dataProvider argumentsProvider
	 */

	public function testItConvertsValueToString($inputValue, $expectedValue)
	{
		$arguments = new Arguments(['someKey' => $inputValue]);

		$value = $arguments->getArgumentValue('someKey');

		$this->assertSame($expectedValue, $value);
	}
}
