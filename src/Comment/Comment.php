<?php

namespace App\Comment;

use App\Post\Post;
use App\User\User;

class Comment
{
	private int $id;
	private User $user;
	private Post $post;
	private string $text;

	public function __construct(int $id, Object $faker, User $user, Post $post)
	{
		$this->id = $id;
		$this->text = $faker->text(50);
		$this->user = $user;
		$this->post = $post;
	}

	public function __toString()
	{
		return $this->text;
	}
}
