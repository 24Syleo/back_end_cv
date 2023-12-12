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

    #[Route('/api/logout', name: 'api_logout', methods: ['GET'])]
    public function logout()
    {
        return $this->json([
            'message' => "Vous êtes déconnecté"
        ]);
    }
}
