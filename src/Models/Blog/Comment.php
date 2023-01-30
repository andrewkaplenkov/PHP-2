<?php

namespace App\Models\Blog;

use App\Models\Blog\Post;
use App\Models\User\Person;
use App\Models\UUID\UUID;

class Comment
{
	private UUID $id;
	private string $text;
	private UUID $person_id;
	private UUID $post_id;

	public function __construct(UUID $id, string $text, UUID $person_id, UUID $post_id)
	{
		$this->id = $id;
		$this->text = $text;
		$this->person_id = $person_id;
		$this->post_id = $post_id;
	}

	public function getId(): UUID
	{
		return $this->id;
	}

	public function getText(): string
	{
		return $this->text;
	}

	public function getUserId(): UUID
	{
		return $this->person_id;
	}

	public function getPostId(): UUID
	{
		return $this->post_id;
	}
}
