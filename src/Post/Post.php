<?php

namespace App\Post;

use App\User\User;

class Post
{
	private int $id;
	private User $user;
	private string $title;
	private string $description;

	public function __construct(int $id, Object $faker, User $user)
	{
		$this->id = $id;
		$this->title = $faker->text(10);
		$this->description = $faker->text(100);
		$this->user = $user;
	}

	public function __toString()
	{
		return $this->title . ' >>> ' . $this->description;
	}
}
