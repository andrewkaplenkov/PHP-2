<?php

namespace App\UniTests\Container;


class ClassDependingOnAnother
{
	public function __construct(
		private ClassWithoutDependencies $one,
		private ClassWithParameter $two
	) {
	}
}
