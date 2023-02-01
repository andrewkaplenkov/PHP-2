<?php

namespace App\Models;

class Post
{
	private UUID $id;
	private User $user;
	private string $title;
	private string $text;

	public function __construct(UUID $id, User $user, string $title, string $text)
	{
		$this->id = $id;
		$this->user = $user;
		$this->title = $title;
		$this->text = $text;
	}

	public function getId(): UUID
	{
		return $this->id;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getText(): string
	{
		return $this->text;
	}
}
