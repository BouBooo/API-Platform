<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenerateRecipeController extends AbstractController
{
    public function __invoke(Meal $data, Request $request, RecipeRepository $recipeModel)
    {
        $queryItems = explode (',', $request->query->get('items'));

        foreach($data->getRecipes() as $recipe) {
            $recipes[] = $recipe->getName();
            $items[] = $recipe->getItems();
        }

        return $recipeModel->findByQueryItems($queryItems, $data);
    }
}
