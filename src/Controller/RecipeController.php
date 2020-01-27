<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    public function __construct(ObjectManager $manager) {
        $this->manager = $manager;
    }

    public function __invoke(Recipe $data, Request $request) {
        // dd($request->query->get('name'));
        $data->setName($request->query->get('name') ?? $data->getName());
        $this->manager->flush();
        return $data;
    }
}
