<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/api/create_user', 
            name: 'create_user',
            methods: ['POST'])]
    #[Route('/api/update_user/{id}', 
            name: 'update_user',
            methods: ['PUT'])]
    public function createUser(?int $id, Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $passHasher, UserRepository $userRepo): JsonResponse
    {
        if ($id)
        {
            $user = $userRepo->find($id);
        } else 
        {
            $user = new User();
        }
        $data = json_decode($req->getContent(), true);
        if(is_array($data) && count($data) > 4)
        {
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setEmail($data['email']);
            $user->setPassword($passHasher->hashPassword($user, $data['password']));
            $user->setAvatar($data['avatar']);
            $user->getRoles();
            $user->setRoles(['ROLE_USER']);
        }
        if (!$id)
        {
            $em->persist($user);
            $em->flush();
        } else 
        {
            $em->flush();
        }

        return $this->json([
            "user" => $user,
        ]);
    }

    #[Route('/api/user/{id}', 
            name: 'get_user',
            methods: ['GET'])]
    public function show(int $id, UserRepository $userRepo)
    {
        if($id)
        {
            $user = $userRepo->find($id);
        }

        return $this->json([
            "user" => $user,
        ]);
    } 
}
