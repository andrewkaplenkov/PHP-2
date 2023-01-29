<?php

namespace App\Models\Blog;

use App\Models\User\Person;

class Post
{
	private int $id;
	private Person $person;
	private string $title;
	private string $description;

	public function __construct(int $id, string $title, string $description, Person $person)
	{
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->person = $person;
	}

	public function __toString()
	{
		return $this->title . ' >>> ' . $this->description;
	}
}
