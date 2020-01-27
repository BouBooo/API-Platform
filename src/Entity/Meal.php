<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 * @ApiResource(itemOperations={ 
 *      "GET" = {
 *          "swagger_context"= {
 *          "summary"="Récupérer les repas",
 *          "descripion"="Yes la description"
 *          }
 *      },
 *       "PUT" = {
 *          "swagger_context"= {
 *          "summary"="Modifier un repas en particulier",
 *          "descripion"="Yes la description"
 *          }
 *       },
 *       "DELETE" = {
 *          "swagger_context"= {
 *          "summary"="Supprimer un repas en particulier",
 *          "descripion"="Yes la description"
 *          }
 *      }, 
 *      "testCustom" = {
 *          "method" = "GET",
 *          "path"  = "/meals/{id}/generate",
 *          "controller" = "App\Controller\GenerateRecipeController",
 *          "swagger_context" = {
 *              "summary" = "Test custom route",
 *              "description" = "blablabla",
 *              "parameters"= {
 *                  {
 *                      "name" = "list",
 *     				    "in" = "body",
 *     			        "type" = "array",
 *                      "required" = true
 *                  },
 *     			}   
 *          }
 *      }
 *  } )
 */
class Meal
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
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", mappedBy="meal")
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
            $recipe->setMeal($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): self
    {
        if ($this->recipes->contains($recipe)) {
            $this->recipes->removeElement($recipe);
            // set the owning side to null (unless already changed)
            if ($recipe->getMeal() === $this) {
                $recipe->setMeal(null);
            }
        }

        return $this;
    }
}
