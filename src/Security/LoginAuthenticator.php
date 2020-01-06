<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

	/**
	 * @var EntityManagerInterface
	 */
    private $entityManager;
	/**
	 * @var UrlGeneratorInterface
	 */
    private $urlGenerator;
	/**
	 * @var CsrfTokenManagerInterface
	 */
    private $csrfTokenManager;
	/**
	 * @var UserPasswordEncoderInterface
	 */
    private $passwordEncoder;
	/**
	 * @var Security
	 */
    private $security;
	/**
	 * @var ContainerInterface
	 */
    private $container;

	/**
	 * LoginAuthenticator constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param UrlGeneratorInterface $urlGenerator
	 * @param CsrfTokenManagerInterface $csrfTokenManager
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param Security $security
	 * @param ContainerInterface $container
	 */
    public function __construct(
    	EntityManagerInterface $entityManager,
		UrlGeneratorInterface $urlGenerator,
		CsrfTokenManagerInterface $csrfTokenManager,
		UserPasswordEncoderInterface $passwordEncoder,
		Security $security,
		ContainerInterface $container
	)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
        $this->container = $container;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
    	$jsonData = \json_decode($request->getContent(), true);

    	$user = $this->security->getUser();

    	if ($user) {
			if ($user->getEmail() !== $jsonData['email']) {
				$this->container->get('security.token_storage')->setToken(null);
				$request->getSession()->invalidate();
			}
		}

		return [
			'email' => $jsonData['email'],
			'password' => $jsonData['password'],
		];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
	{
		if (!$credentials['email'] || !$credentials['password']) {
			return null;
		}

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
        	'email' => $credentials['email']
		]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		return null;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
	{
		return new JsonResponse(['message' => 'Authentication Required'], Response::HTTP_UNAUTHORIZED);
	}

	public function supportsRememberMe()
	{
		return false;
	}

	/**
	 * @inheritDoc
	 */
	protected function getLoginUrl() {}

	/**
	 * @inheritDoc
	 */
	public function getPassword($credentials): ?string
	{
		return $credentials['password'];
	}
}
