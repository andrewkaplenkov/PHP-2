<?php

namespace App\Models;

class Like
{
	public function __construct(
		private UUID $id,
		private UUID $post_id,
		private UUID $user_id,
	) {
	}

	public function id(): UUID
	{
		return $this->id;
	}
	public function post_id(): UUID
	{
		return $this->post_id;
	}
	public function user_id(): UUID
	{
		return $this->user_id;
	}
}
