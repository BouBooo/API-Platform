<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CaloriesController extends AbstractController
{
    public function __invoke(Meal $data, Request $request, RecipeRepository $recipeModel)
    {
        $test = [];
        $recipesToSend = [];
        $itemsCal = [];

        if (!$request->get('calories')) {
        	return new JsonResponse(['message' => 'query parameter is required'], Response::HTTP_BAD_REQUEST);
        }
        $calories = intval($request->get('calories'));
        $recipes = $data->getRecipes();

        foreach($recipes as $recipe) {
            foreach($recipe->getItems() as $item) {
                array_push($itemsCal, $item->getCalories());
            }
            $recipeCal = array_sum($itemsCal);
            $itemsCal = [];
            array_push($test, $recipeCal);
            if($recipeCal <= $calories) {
                array_push($recipesToSend, $recipe);
            }
        } 
        return $recipesToSend;
    }
}
