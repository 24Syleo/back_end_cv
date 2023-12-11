<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\ColumnsRepository;
use Exception;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/api/create_task', name: 'create_task', methods:['POST'])]
    #[Route('/api/update_task/{task_id}', name: 'update_task', methods: ['PUT'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function createTask(ColumnsRepository $colRepo, TaskRepository $taskRepo, ?int $task_id, Request $req, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        try {
            if ($task_id) {
                $task = $taskRepo->find($task_id);
            } else {
                $task  = new Task();
            }
            $data = json_decode($req->getContent(), true);
            if ($data) {
                $date = new \DateTimeImmutable('');
                $task->setTitle($data['title']);
                $task->setCols($colRepo->find($data['colId']));
                $task->setDescription($data['description']);
                if(!$task_id){
                    $task->setCreatedAt($date);
                }
            }
            $errors = $validator->validate($task);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    return $this->json([
                        "error" => $error->getMessage()
                    ]);
                }
            }
            if (!$task_id) {
                $em->persist($task);
            }
            $em->flush();
            return $this->json([
                'task' => $task
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }
}
