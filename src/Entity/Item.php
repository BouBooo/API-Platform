<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ApiResource(
 *  itemOperations={ 
 *      "GET" = {
 *          "swagger_context"= {
 *          "summary"="Récupérer les items",
 *          "descripion"="Yes la description"
 *          }
 *      },
 *       "PUT" = {
 *          "swagger_context"= {
 *          "summary"="Modifier un item en particulier",
 *          "descripion"="Yes la description"
 *          }
 *       },
 *       "DELETE" = {
 *          "swagger_context"= {
 *          "summary"="Supprimer un item en particulier",
 *          "descripion"="Yes la description"
 *          }
 *      }, 
 *  } 
 * )
 */
class Item
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $infos;

    /**
     * @ORM\Column(type="integer")
     */
    private $protein;

    /**
     * @ORM\Column(type="integer")
     */
    private $glucid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vitamin;

    /**
     * @ORM\Column(type="integer")
     */
    private $calories;

    /**
     * @ORM\Column(type="integer")
     */
    private $sugar;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="items")
     * @ApiSubresource()
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recipe", inversedBy="items")
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getGlucid(): ?int
    {
        return $this->glucid;
    }

    public function setGlucid(int $glucid): self
    {
        $this->glucid = $glucid;

        return $this;
    }

    public function getVitamin(): ?int
    {
        return $this->vitamin;
    }

    public function setVitamin(?int $vitamin): self
    {
        $this->vitamin = $vitamin;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getSugar(): ?int
    {
        return $this->sugar;
    }

    public function setSugar(int $sugar): self
    {
        $this->sugar = $sugar;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
        }

        return $this;
    }
}
