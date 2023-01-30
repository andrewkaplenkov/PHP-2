<?php

namespace App\Repositories\BlogRepository\CommentsRepository;

use App\Models\Blog\Comment;
use App\Models\UUID\UUID;

interface CommentsRepositoryInterface
{
	public function saveComment(Comment $comment): void;

	public function getCommentById(UUID $id): Comment;
}
