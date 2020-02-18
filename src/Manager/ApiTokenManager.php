<?php

namespace App\Manager;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiTokenManager extends AbstractController
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;
	/**
	 * @var ObjectRepository
	 */
	private $apiTokenRepository;

	/**
	 * ApiTokenManager constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->em = $entityManager;
		$this->apiTokenRepository = $entityManager->getRepository(ApiToken::class);
	}

	/**
	 * @param User $user
	 *
	 * @return ApiToken
	 */
	public function create(User $user)
	{
		$tokens = $this->generateTokens();

		$apiToken = new ApiToken($tokens['accessToken'], $tokens['refreshToken'], $tokens['expirationDate'], $user);

		// Insert new ApiToken in DB
		$this->em->persist($apiToken);
		$this->em->flush();

		return $apiToken;
	}

	/**
	 * @param ApiToken $apiToken
	 *
	 * @return ApiToken
	 */
	public function refreshToken(ApiToken $apiToken)
	{
		$tokens = $this->generateTokens();

		$apiToken->setAccessToken($tokens['accessToken']);
		$apiToken->setRefreshToken($tokens['refreshToken']);
		$apiToken->setExpirationDate($tokens['expirationDate']);

		$this->em->persist($apiToken);
		$this->em->flush();

		return $apiToken;
	}

	/**
	 * @return array
	 */
	private function generateTokens()
	{
		$tokens = [];

		do {
			$accessToken = sha1(random_bytes(10));
		} while ($this->accessTokenAlreadyExists($accessToken));

		$tokens['accessToken'] = $accessToken;

		do {
			$refreshToken = '__' . sha1(random_bytes(10));
		} while ($this->refreshTokenAlreadyExists($refreshToken));

		$tokens['refreshToken'] = $refreshToken;
		$tokens['expirationDate'] = (new \DateTime('now'))->add(new \DateInterval('P30D'));

		return $tokens;
	}

	/**
	 * @param string $accessToken
	 *
	 * @return bool
	 */
	private function accessTokenAlreadyExists(string $accessToken)
	{
		$apiToken = $this->apiTokenRepository->findOneBy([
			'accessToken' => $accessToken
		]);

		return !is_null($apiToken);
	}

	/**
	 * @param string $refreshToken
	 *
	 * @return bool
	 */
	private function refreshTokenAlreadyExists(string $refreshToken)
	{
		$apiToken = $this->apiTokenRepository->findOneBy([
			'refreshToken' => $refreshToken
		]);

		return !is_null($apiToken);
	}
}