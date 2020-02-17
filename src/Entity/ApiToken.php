<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     shortName="Tokens",
 *     collectionOperations={},
 *     itemOperations={
 *     		"refresh_token" = {
 *     			"method" = "POST",
 *     			"route_name" = "refresh_token",
 *     			"swagger_context": {
 *     				"summary" = "Refresh a token",
 *     				"description" = "Refresh a token",
 *              	"parameters" = {
 *                  	{
 *                      	"name" = "body",
 *     				    	"in" = "body",
 *                      	"required" = true,
 *     						"schema" = {
 *     							"type" = "object",
 *     							"required" = {
 *     								"refreshToken"
 *     							},
 *     							"properties" = {
 *     								"refreshToken" = {
 *										"type" = "string"
 * 									}
 *     							}
 *     					}
 *                  },
 *     			},
 *     			"responses" = {
 *     				200 = {
 *     					"description" = "message: access_token_refreshed,
 * 										 user: test@test.com,
 *	 									 accessToken: 413hzjabd9ndaiadnokdmzdjzhu,
 *	 									 refreshToken: __JDAoiaoap38dpj2pjod9danipre,
 *	 									 expirationDate: 23/04/2020 12:34:03"
 *     				},
 *     				400 = {
 *     					"description" = "message: refresh_token_not_found"
 *     				}
 *     			}
 *
 *     		}
 * 		}
 * 	}
 * )
 *
 *
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
{



    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accessToken;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refreshToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expirationDate;

	/**
	 * ApiToken constructor.
	 *
	 * @param string $accessToken
	 * @param string $refreshToken
	 * @param \DateTime $expirationDate
	 * @param User $user
	 */
	public function __construct(string $accessToken, string $refreshToken, \DateTime $expirationDate, User $user)
	{
		$this->accessToken = $accessToken;
		$this->refreshToken = $refreshToken;
		$this->expirationDate = $expirationDate;
		$this->user = $user;
	}


	public function getId(): ?int
  {
	  return $this->id;
  }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function isValid($now)
    {
        return $now < $this->expirationDate;
    }
}
