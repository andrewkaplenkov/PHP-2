<?php

namespace App\HTTP\Response;


class UnsuccessfullResponse extends Response
{
	protected const SUCCESS = false;

	public function __construct(private string $errorReason = 'Something went wrong')
	{
	}

	protected function payload(): array
	{
		return ['errorReason' => $this->errorReason];
	}
}
