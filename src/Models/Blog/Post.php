<?php

namespace App\Models\Blog;

use App\Models\User\Person;
use App\Models\UUID\UUID;

class Post
{
	private UUID $id;
	private string $title;
	private string $description;
	private UUID $author_id;


	public function __construct(UUID $id, string $title, string $description, UUID $author_id)
	{
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->author_id = $author_id;
	}

	public function getId(): UUID
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getUserId(): UUID
	{
		return $this->author_id;
	}
}
