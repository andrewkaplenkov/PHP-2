<?php

namespace App\Controllers\Like;

use App\Models\Like;
use App\Models\UUID;
use PDOStatement;

interface LikeControllerInteface
{

	public function save(UUID $post_id, UUID $user_id): void;

	public function getByPostId(UUID $post_id): array;

	public function delete(UUID $post_id, UUID $user_id): void;
}
