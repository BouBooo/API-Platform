<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Meal;
use App\Entity\Recipe;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $items = [];
        // $meals = ['Repas', 'Petit-déjeuner', 'Collation'];
        // $mealsEntities = [];

        // for($c = 0; $c < 5; $c++) {
        //     $category = new Category();
        //     $category->setName('Categorie ' . $c)
        //             ->setDescription('Description categorie ' . $c);
        //     $manager->persist($category);

        //     for($i = 0; $i < 7; $i++) {
        //         $item = new Item();
        //         $item->setName('Item name ' . $i)
        //             ->setInfos('Infos for item ' . $i)
        //             ->setProtein(22)
        //             ->setGlucid(36)
        //             ->setVitamin(6)
        //             ->setCalories(520)
        //             ->setSugar(12)
        //             ->setCreatedAt(new \DateTime())
        //             ->setCategory($category);
        //         $manager->persist($item);
        //         array_push($items, $item);
        //     }
        // }

        // for($m = 0; $m < sizeof($meals); $m++) {
        //     $meal = new Meal();
        //     $meal->setName($meals[$m]);
        //     array_push($mealsEntities, $meal);
        //     $manager->persist($meal);

        //     for($r = 0; $r < 4; $r++) {
        //         $recipe = new Recipe();
        //         $recipe->setName('Recipe : ' . $r)
        //                 ->setDescription('Description for recipe ' . $r)
        //                 ->setMeal($meal);
        //             for($n = 0; $n < 4; $n++) {
        //                 $recipe->addItem($items[array_rand($items, 1)]);
        //             }   
        //         $manager->persist($recipe);
        //     }
        // }

        // $manager->flush();
    }
}
