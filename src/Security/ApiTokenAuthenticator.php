<?php

namespace App\Security;

use App\Entity\ApiToken;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
	const AUTH_TOKEN = "X-AUTH-TOKEN";

	/**
	 * @var ObjectManager
	 */
	private $manager;

	/**
	 * ApiTokenAuthenticator constructor.
	 *
	 * @param EntityManagerInterface $manager
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	public function supports(Request $request)
    {
        return $request->headers->has(self::AUTH_TOKEN);
    }

    public function getCredentials(Request $request)
    {
        return [
        	'accessToken' => $request->headers->get(self::AUTH_TOKEN)
		];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials['accessToken']) {
     		return null;
		}

        /** @var ApiToken $apiToken */
        $apiToken = $this->manager->getRepository(ApiToken::class)->findOneBy([
			'accessToken' => $credentials['accessToken']
		]);

        if (is_null($apiToken)) {
        	return null;
		}

        $now = new \DateTime('now');
        if (!$apiToken->isValid($now)) {
        	return null;
		}

        return $apiToken->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['message' => 'access_token_invalid'], Response::HTTP_BAD_REQUEST);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

	/**
	 * @param Request $request
	 * @param AuthenticationException|null $authException
	 *
	 * @return JsonResponse|Response
	 */
    public function start(Request $request, AuthenticationException $authException = null)
    {
		return new JsonResponse(['message' => 'access_token_invalid'], Response::HTTP_BAD_REQUEST);
	}

    public function supportsRememberMe()
    {
        return null;
    }
}