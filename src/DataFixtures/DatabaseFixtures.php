<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Meal;
use App\Entity\Recipe;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DatabaseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $items = [];
        $meals = [];
        $iteration = 0;
        $itemsName = ['Poulet', 'Citron', 'Blé', 'Carotte', 'Oeuf', 'Sucre', 'Tomate', 
            'Miel', 'Haricots', 'Pâtes', 'Riz', 'Avoine', 'Semoule', 'Saumon', 'Boeuf', 'Pomme', 'Chocolat', 'Orange'
        ];
        $categoriesNames = ['Viandes', 'Céréales', 'Produits laitiers', 'Corps gras'];
        $mealsName = ['Repas', 'Petit-déjeuner', 'Collation'];
        $recipesName = ['Recette A', 'Recette B', 'Recette C', 'Recette D',
            'Recette E','Recette F','Recette G','Recette H',
            'Recette I', 'Recette J', 'Recette K', 'Recette L'
        ];

        for($c = 0; $c < 4; $c++) {
            $category = new Category();
            $category->setName($categoriesNames[$c])
                    ->setDescription('Description de la catégorie ' . $category->getName());
            $manager->persist($category);

            for($i = 0; $i < 7; $i++) {
                $item = new Item();
                // dd($itemsName[array_rand($itemsName, 1)]);
                $item->setName($itemsName[array_rand($itemsName, 1)])
                    ->setInfos('Infos for item ' . $item->getName())
                    ->setProtein(mt_rand(4, 26))
                    ->setGlucid(mt_rand(12, 50))
                    ->setVitamin(mt_rand(4, 8))
                    ->setCalories(mt_rand(50, 200))
                    ->setSugar(mt_rand(5, 20))
                    ->setCreatedAt(new \DateTime())
                    ->setCategory($category);
                $manager->persist($item);
                array_push($items, $item);
                
            }
        }

        for($m = 0; $m < sizeof($mealsName); $m++) {
                $meal = new Meal();
                $meal->setName($mealsName[$m]);
                array_push($meals, $meal);
                $manager->persist($meal);
    
                for($r = 0; $r < 4; $r++) {
                    $recipe = new Recipe();
                    $recipe->setName($recipesName[$iteration])
                            ->setDescription('Description for recipe ' . $recipe->getName())
                            ->setMeal($meal);
                        for($n = 0; $n < 3; $n++) {
                            $recipe->addItem($items[array_rand($items, 1)]);
                        }  
                    $iteration++; 
                    $manager->persist($recipe);
                }
            }

        $manager->flush();
    }
}
