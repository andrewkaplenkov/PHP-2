<?php

namespace App\Repositories\BlogRepository\PostsRepository;

use App\Models\Blog\Post;
use App\Models\UUID\UUID;

interface PostsRepositoryInterface
{
	public function savePost(Post $post): void;

	public function getPostById(UUID $id): Post;
}
