<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthentificationController extends AbstractController
{
    #[Route('/api/login_check', name: 'api_login', methods:['POST'])]
    public function login(#[CurrentUser] ?User $user, EntityManagerInterface $em): Response
    {
        try {
        
        if (null === $user) 
        {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }



        return $this->json([
            'user' => $user
        ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
