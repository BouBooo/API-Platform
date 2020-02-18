<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\ApiTokenManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @Route(
	 *     name="app_login",
	 *     path="/api/auth/login",
	 *     methods={"POST"},
	 *     defaults={
	 *     		"_controller"="\App\Controller\SecurityController::login",
	 *     		"_api_ressource_class"="App\Entity\User",
	 *     		"_api_item_operation_name"="login"
	 * })
	 *
	 * @return Response
	 */
    public function login() : Response
    {
    	/** @var User $user */
    	$user = $this->getUser();
    	$accessToken = null;
    	$refreshToken = null;
    	$expirationDate = null;

    	foreach ($user->getApiTokens() as $apiToken) {
    		$accessToken = $apiToken->getAccessToken();
    		$refreshToken = $apiToken->getRefreshToken();
    		$expirationDate = $apiToken->getExpirationDate();
		}

    	return new JsonResponse([
    		'accessToken' => $accessToken,
			'refreshToken' => $refreshToken,
			'expirationDate' => $expirationDate->format('d/m/Y H:i:s')
		], Response::HTTP_OK);
    }

	/**
	 * @Route(
	 *     name="app_register",
	 *     path="/api/auth/register",
	 *     methods={"POST"},
	 *     defaults={
	 *     		"_controller"="\App\Controller\SecurityController::register",
	 *     		"_api_ressource_class"="App\Entity\User",
	 *     		"_api_item_operation_name"="register"
	 * })
	 *
	 * @param Request $request
	 * @param EntityManagerInterface $manager
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param ApiTokenManager $apiTokenManager
	 *
	 * @return JsonResponse
	 */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, ApiTokenManager $apiTokenManager)
	{
		$jsonData = \json_decode($request->getContent(), true);
		if (null === $jsonData) {
			return new JsonResponse(['message' => 'json_malformatted'], Response::HTTP_BAD_REQUEST);
		}

		if (empty($jsonData['email']) || empty($jsonData['password'])) {
			return new JsonResponse(['message' => 'missing_fields'], Response::HTTP_BAD_REQUEST);
		}

		if (!filter_var($jsonData['email'], FILTER_VALIDATE_EMAIL)) {
			return new JsonResponse(['message' => 'invalid_email_format'], Response::HTTP_BAD_REQUEST);
		}

		$userAlreadyExist = $this->entityManager->getRepository(User::class)->findOneBy([
			'email' => $jsonData['email']
		]);

		if ($userAlreadyExist) {
			return new JsonResponse(['message' => 'user_already_exist'], Response::HTTP_FOUND);
		}

		$user = new User();
		$user->setEmail($jsonData['email'])
			->setPassword($passwordEncoder->encodePassword($user, $jsonData['password']));

		$manager->persist($user);
		$manager->flush();

		$apiTokenManager->create($user);

		return new JsonResponse(['message' => 'account_created'], Response::HTTP_OK);
	}
}
