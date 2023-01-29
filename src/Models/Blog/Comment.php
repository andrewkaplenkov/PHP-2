<?php

namespace App\Models\Blog;

use App\Models\Blog\Post;
use App\Models\User\Person;

class Comment
{
	private int $id;
	private Person $person;
	private Post $post;
	private string $text;

	public function __construct(int $id, string $text, Person $person, Post $post)
	{
		$this->id = $id;
		$this->text = $text;
		$this->person = $person;
		$this->post = $post;
	}

	public function __toString()
	{
		return $this->text;
	}
}
