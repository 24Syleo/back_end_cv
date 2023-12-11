<?php

namespace App\Controller;

use App\Entity\Columns;
use App\Repository\ColumnsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColumnsController extends AbstractController
{
    #[Route('/api/create_columns/{column_id}', name: 'create_columns', methods: ['POST','PUT'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createColumns(?int $column_id,Request $req, ValidatorInterface $validator, EntityManagerInterface $em, ColumnsRepository $colRepo): JsonResponse
    {
        try {
            $user = $this->getUser();
            if ($column_id) {
                $col = $colRepo->find($column_id);
            } else {
                $col  = new Columns();
            }
            $data = json_decode($req->getContent(), true);
            if ($data) {
                $col->setTitle($data['title']);
                $col->setUser($user);
                $col->setPosition($data['position']);
            }
            $errors = $validator->validate($col);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    return $this->json([
                        "error" => $error->getMessage()
                    ]);
                }
            }
            if (!$column_id) {
                $em->persist($col);
            }
            $em->flush();
            return $this->json([
                "column" => $col,
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }

    #[Route('/api/get_columns', name: 'get_columns', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function showColumns(ColumnsRepository $colRepo): JsonResponse
    {
        try {
            $user = $this->getUser();
            $cols = $colRepo->findBy(['User' => $user]);

            return $this->json([
                "columns" => $cols,
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }
}
