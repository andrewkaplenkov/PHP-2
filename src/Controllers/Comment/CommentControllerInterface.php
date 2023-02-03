<?php


namespace App\Controllers\Comment;

use App\Models\Comment;
use App\Models\UUID;

interface CommentControllerInterface
{
	public function makeComment(Comment $comment): void;

	public function getCommentById(UUID $id): Comment;

	public function deleteComment(UUID $id): void;
}
