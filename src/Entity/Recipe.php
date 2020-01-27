<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 * @ApiResource(
 * itemOperations={ 
 *      "GET" = {
 *          "swagger_context"= {
 *          "summary"="Récupérer les recipes",
 *          "descripion"="Yes la description"
 *          }
 *      },
 *       "PUT" = {
 *          "swagger_context"= {
 *          "summary"="Modifier une recipe en particulier",
 *          "descripion"="Yes la description"
 *          }
 *       },
 *       "DELETE" = {
 *          "swagger_context"= {
 *          "summary"="Supprimer une recipe en particulier",
 *          "descripion"="Yes la description"
 *          }
 *      }
 *  } 
 * )
 */
class Recipe
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
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item", mappedBy="recipes")
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Meal", inversedBy="recipes")
     */
    private $meal;

    public function __construct()
    {
        $this->items = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addRecipe($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->removeRecipe($this);
        }

        return $this;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    public function setMeal(?Meal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }
}