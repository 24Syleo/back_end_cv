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
    public function createUser(Request $req, UserRepository $userRepo, EntityManagerInterface $em, UserPasswordHasherInterface $passHasher): JsonResponse
    {
        $data = json_decode($req->getContent(), true);
        $user = new User();
        if(is_array($data) && count($data) > 4)
        {
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setEmail($data['email']);
            $user->setAvatar($data['avatar']);
            dump($user);

        }

        return $this->json([
            "user" => $data,
        ]);
    }
}
