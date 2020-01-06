<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessMessageHandler implements LogoutSuccessHandlerInterface
{
	/**
	 * @inheritDoc
	 */
	public function onLogoutSuccess(Request $request)
	{
		return new JsonResponse(['message' => 'logout_success'], Response::HTTP_OK);
	}
}