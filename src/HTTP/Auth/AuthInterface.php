<?php

namespace App\HTTP\Auth;

use App\HTTP\Request\Request;
use App\Models\User;

interface AuthInterface
{
	public function user(Request $request): User;
}
