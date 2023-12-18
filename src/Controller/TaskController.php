<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\ColumnsRepository;
use App\Repository\tasksRepository;
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
                $task->setPosition($data['position']);
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

    #[Route('/api/move_task/{task_id}', name: 'move_task', methods: ['PATCH'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function moveTasks(Request $req ,int $task_id, TaskRepository $taskRepo, ColumnsRepository $colRepo ,EntityManagerInterface $em): JsonResponse
    {
        try {
            $task = $taskRepo->find($task_id);
            if (!$task) {
                return $this->json([
                    "error" => "Pas de tache",
                ]);
            }
            $data = json_decode($req->getContent(), true);
            $col = $colRepo->find($data["droppableId"]);
            $task->setCols($col);
            $em->flush();
            return $this->json([
                "task" => $task,
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }


    #[Route('/api/get_tasks', name: 'get_tasks', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function showtasks(TaskRepository $taskRepo, ColumnsRepository $colRepo): JsonResponse
    {
        try {
            $user = $this->getUser();
            $cols = $colRepo->findBy(['User' => $user]);
            foreach ($cols as $col) {
                $tasks[] = $taskRepo->findBy(['cols' => $col->getId()],["position" => "ASC"]);
            }

            return $this->json([
                "tasks" => $tasks
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }


    #[Route('/api/delete_task/{task_id}', name: 'delete_task', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function deletetasks(int $task_id, TaskRepository $taskRepo, EntityManagerInterface $em): JsonResponse
    {
        try {
            $task = $taskRepo->find($task_id);
            if (!$task) {
                return $this->json([
                    "error" => "Pas de tache",
                ]);
            }
            $em->remove($task);
            $em->flush();
            return $this->json([
                "id" => $task_id,
                "success" => "Tache supprimée"
            ]);
        } catch (Exception $e) {
            return $this->json([
                "error" => $e,
            ]);
        }
    }

}
