<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 *  collectionOperations = {},
 *  shortName="Authentication",
 *     itemOperations = {
 *    	"register" = {
 *     		"method" = "POST",
 *     		"route_name" = "app_register",
 *     		"swagger_context": {
 *     			"summary" = "Register",
 *     			"description" = "Register",
 * 				"parameters" = {
 *                  {
 *                      "name" = "body",
 *     				    "in" = "body",
 *                      "required" = true,
 *     					"schema" = {
 *     						"type" = "object",
 *     						"required" = {
 *     							"email",
 *     							"password"
 *     						},
 *     						"properties" = {
 *     							"email" = {
 *     								"type" = "string"
 *     							},
 *     							"password" = {
 *     								"type" = "string"
 *     							}
 *     						}
 *     					}
 *					}
 *                  },
 *     				"responses" = {
 *     					200 = {
 *     						"description" = "message: account_created"
 *     					},
 *     					400 = {
 *     						"description" = "message: missing_fields"
 *     					}
 *     			}
 *          }
 *	 	},
 *     	"login" = {
 *     		"method" = "POST",
 *     		"route_name" = "app_login",
 *     		"swagger_context" = {
 *     			"summary" = "Login",
 *     			"description" = "Login",
 *              "parameters"= {
 *                  {
 *                      "name" = "body",
 *     				    "in" = "body",
 *                      "required" = true,
 *     					"schema" = {
 *     						"type" = "object",
 *     						"required" = {
 *     							"email",
 *     							"password"
 *     						},
 *     						"properties" = {
 *     							"email" = {
 *     								"type" = "string"
 *     							},
 *     							"password" = {
 *     								"type" = "string"
 *     							}
 *     						}
 *     					}
 *
 *                  },
 *     			},
 *     			"responses" = {
 *     				200 = {
 *     					"description" = "accessToken: 49b8e963ce8bba60e1edfc03c1f70260fc9bd23a,
										refreshToken: 01a2238d6949b65471f02e02b7445770a9e4217c,
										expirationDate: 18/03/2020 21:16:19"
 *     				},
 *     				400 = {
 *     					"description" = "message = invalid_credentials"
 *     				}
 *     			}
 *              
 *	 		}
 *     	}
 *	 }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ApiToken", mappedBy="user")
     */
    private $apiTokens;

    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }
}
