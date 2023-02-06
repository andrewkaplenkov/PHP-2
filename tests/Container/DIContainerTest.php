<?php


namespace App\Tests\Container;

use App\Container\DIContainer;
use App\Controllers\User\UserController;
use App\Controllers\User\UserControllerInterface;
use App\Exceptions\NotFoundException;
use App\UniTests\Container\ClassDependingOnAnother;
use App\UniTests\Container\ClassWithoutDependencies;
use App\UniTests\Container\ClassWithParameter;
use PDO;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{

	public function testItThrowsAnExceptionIfCannotResolveType(): void
	{

		$container = new DIContainer();

		$this->expectException(NotFoundException::class);
		$this->expectExceptionMessage("Cannot resolve App\Tests\Container\SomeClass");

		$container->get(SomeClass::class);
	}

	public function testItResolvesClassWithoutDependencies(): void
	{
		$container = new DIContainer();

		$someClass = $container->get(ClassWithoutDependencies::class);

		$this->assertInstanceOf(ClassWithoutDependencies::class, $someClass);
	}

	public function testItResolvesClassByContract(): void
	{

		$container = new DIContainer();

		$container->bind(
			PDO::class,
			new PDO("sqlite:blog.db", null, null, [
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			])
		);

		$container->bind(
			UserControllerInterface::class,
			UserController::class
		);

		$object = $container->get(UserControllerInterface::class);

		$this->assertInstanceOf(UserControllerInterface::class, $object);
	}

	public function testItReturnsPredefinedObject(): void
	{
		$container = new DIContainer();

		$container->bind(
			ClassWithParameter::class,
			new ClassWithParameter(42)
		);

		$object = $container->get(ClassWithParameter::class);

		$this->assertInstanceOf(
			ClassWithParameter::class,
			$object
		);

		$this->assertSame(42, $object->value());
	}

	public function testItResolvesClassWithDependencies(): void
	{
		$container = new DIContainer();

		$container->bind(
			ClassWithParameter::class,
			new ClassWithParameter(42)
		);

		$object = $container->get(ClassDependingOnAnother::class);

		$this->assertInstanceOf(
			ClassDependingOnAnother::class,
			$object
		);
	}
}
