<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColumnsController extends AbstractController
{
    #[Route('/api/create_columns', name: 'create_columns', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createColumns()
    {
        return $this->json([
            "columns" => "columns",
        ]);
    }
}
