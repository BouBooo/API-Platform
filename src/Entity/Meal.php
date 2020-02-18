<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealRepository")
 * @ApiResource(
 *  itemOperations={ 
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
 *              "summary" = "Générer une recette selon le type de repas et les ingrédients souhaités",
 *              "description" = "",
 *              "parameters"= {
 *                  {
 *                      "name" = "list",
 *     				    "in" = "body",
 *     			        "type" = "array",
 *                      "required" = true
 *                  },
 *     			}   
 *          }
 *      },
 *     "maxCalories" = {
 *          "method" = "GET",
 *          "path"  = "/meals/{id}/calories",
 *          "controller" = "App\Controller\CaloriesController",
 *          "swagger_context" = {
 *              "summary" = "Génère les recettes respectant la charge calorique indiquée.",
 *              "description" = ""  
 *          }
 *      }
 *  },
 * normalizationContext={
 *      "groups" = {"meal_normalization"}
 *  }, 
 * )
 */
class Meal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"meal_normalization"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"meal_normalization", "recipe_normalization"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recipe", mappedBy="meal", orphanRemoval=true)
     * @Groups({"meal_normalization"})
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
