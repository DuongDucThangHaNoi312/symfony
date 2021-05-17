<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function showUser(): Response
    {
        $userRepo = $this->getDoctrine()->getRepository(persistentObject: User::class);
        $users = $userRepo->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
}
