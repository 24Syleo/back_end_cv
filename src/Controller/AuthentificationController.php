<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthentificationController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods:['POST'])]
    public function login(#[CurrentUser] ?User $user, EntityManagerInterface $em): Response
    {
        if (null === $user) 
        {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user->setToken(bin2hex(random_bytes(32)));
        $em->flush();

        return $this->json([
            'user' => $user
        ]);
    } 
}
