<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
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
