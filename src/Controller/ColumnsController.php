<?php

namespace App\Controller;

use App\Entity\Columns;
use App\Repository\ColumnsRepository;
use App\Repository\TaskRepository;
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
    #[Route('/api/create_columns', name: 'create_columns', methods: ['POST'])]
    #[Route('/api/update_columns/{column_id}', name: 'update_columns', methods: ['PUT'])]
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
    public function showColumns(ColumnsRepository $colRepo, TaskRepository $taskRepo): JsonResponse
    {
        try {
            $user = $this->getUser();
            $cols = $colRepo->findBy(['User' => $user]);
            $columns = [];

            foreach ($cols as $col) {
                $tasks = $taskRepo->findBy(['cols' => $col]);
    
                $columns[] = [
                    'column' => $col,
                    'tasks' => $tasks,
                ];
            }
            return $this->json([
                "columns" => $columns
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }
    
    #[Route('/api/move_columns/{col_id}', name: 'move_columns', methods: ['PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function moveColumns(Request $req ,int $col_id, ColumnsRepository $colRepo ,EntityManagerInterface $em): JsonResponse
    {
        try {
            $col = $colRepo->find($col_id);
            if (!$col) {
                return $this->json([
                    "error" => "Colonne pas trouvé",
                ]);
            }
            $data = json_decode($req->getContent(), true);
            $col->setPosition($data["position"]);
            $em->flush();
            return $this->json([
                "message" => "position mis à jours"
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }

    #[Route('/api/delete_columns/{column_id}', name: 'delete_columns', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deleteColumns(int $column_id, ColumnsRepository $colRepo, EntityManagerInterface $em): JsonResponse
    {
        try {
            $col = $colRepo->find($column_id);
            if (!$col) {
                return $this->json([
                    "error" => "La colonne n'existe pas"
                ]);
            }
            $em->remove($col);
            $em->flush();
            return $this->json([
                "id"      => $column_id,
                "success" => "colonne supprimé"
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }
}
