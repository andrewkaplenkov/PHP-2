<?php

namespace App\Controllers\Post;

use App\Controllers\User\UserControllerInterface;
use App\Models\Post;
use App\Models\UUID;

interface PostControllerInterface
{

	public function makePost(Post $post): void;

	public function getPostById(UUID $id, UserControllerInterface $userController): Post;
}
