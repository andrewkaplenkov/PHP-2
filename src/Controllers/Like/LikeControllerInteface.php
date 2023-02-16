<?php

namespace App\Controllers\Like;

use App\Models\Like;
use App\Models\UUID;
use PDOStatement;

interface LikeControllerInteface
{
	public function checkIfExists(UUID $post_id, UUID $user_id): void;

	public function save(Like $like): void;

	public function getByPostId(UUID $post_id): array;

	public function delete(UUID $post_id, UUID $user_id): void;
}
