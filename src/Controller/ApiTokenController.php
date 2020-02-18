<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Manager\ApiTokenManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiTokenController extends AbstractController
{

	/**
	 * @Route(
	 *     name="refresh_token",
	 * 	   path="/api/auth/token/refresh",
	 *	   methods={"POST"},
	 *     defaults= {
	 *     		"_controller"="\App\Controller\ApiTokenController::refresh",
	 *     		"_api_ressource_class"="App\Entity\ApiToken",
	 *     		"_api_item_operation_name"="refresh_token"
	 * 	   }
	 * )
	 *
	 *
	 * @param EntityManagerInterface $manager
	 * @param Request $request
	 * @param ApiTokenManager $apiTokenManager
	 *
	 * @return JsonResponse
	 */
	public function refresh(EntityManagerInterface $manager, Request $request, ApiTokenManager $apiTokenManager)
	{
		// Decode request content
		$jsonData = \json_decode($request->getContent(), true);
		if (null === $jsonData) {
			return new JsonResponse(['message' => 'json_malformatted']);
		}

		if (!array_key_exists('refreshToken', $jsonData)) {
			return new JsonResponse(['message' => 'missing_fields'], Response::HTTP_BAD_REQUEST);
		}

		/** @var ApiToken $apiToken */
		$apiToken = $manager->getRepository(ApiToken::class)->findOneBy([
			'refreshToken' => $jsonData['refreshToken']
		]);

		if (is_null($apiToken)) {
			return new JsonResponse(['message' => 'refresh_token_not_found'], Response::HTTP_NOT_FOUND);
		}

		$newApiToken = $apiTokenManager->refreshToken($apiToken);

		return new JsonResponse(['message' => 'access_token_refeshed',
			'user' => $newApiToken->getUser()->getEmail(),
			'accessToken' => $newApiToken->getAccessToken(),
			'refreshToken' => $newApiToken->getRefreshToken(),
			'expirationDate' => $newApiToken->getExpirationDate()->format('d/m/Y H:i:s')
		], Response::HTTP_OK);

	}
}