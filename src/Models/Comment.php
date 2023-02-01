<?php

namespace App\Models;

class Comment
{
	private UUID $id;
	private Post $post;
	private User $user;
	private string $comment;

	public function __construct(UUID $id, Post $post, User $user, string $comment)
	{
		$this->id = $id;
		$this->post = $post;
		$this->user = $user;
		$this->comment = $comment;
	}

	public function getId(): UUID
	{
		return $this->id;
	}

	public function getPost(): Post
	{
		return $this->post;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function getComment(): string
	{
		return $this->comment;
	}
}
