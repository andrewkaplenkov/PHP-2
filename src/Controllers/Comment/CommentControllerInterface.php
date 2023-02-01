<?php


namespace App\Controllers\Comment;

use App\Controllers\Post\PostControllerInterface;
use App\Controllers\User\UserControllerInterface;
use App\Models\Comment;
use App\Models\UUID;

interface CommentControllerInterface
{
	public function makeComment(Comment $comment): void;

	public function getCommentById(UUID $id, PostControllerInterface $postController, UserControllerInterface $userController);
}
