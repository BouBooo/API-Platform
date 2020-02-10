<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
   /**
	 * @Route(
	 *     name="user_account",
	 * 	   path="/api/account",
	 *	   methods={"GET"},
	 *     defaults={
	 *     		"_controller"="\App\Controller\AccountController::currentUserInfos",
	 *     		"_api_ressource_class"="App\Entity\User",
	 *     		"_api_item_operation_name"="user_account"
	 * 	   }
	 * )
	 *
	 *
	 * @return JsonResponse
	 */
    public function currentUserInfos() {
        if (!$this->getUser()) return new JsonResponse(['message' => 'not_connected']);

        return new JsonResponse(['message' => 'my_informations',
            'user' => [
                'email' => $this->getUser()->getEmail(),
                'role' => $this->getUser()->getRoles()
            ]
        ], Response::HTTP_OK);
    }
}
