<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    #[Route('/api/users', name: 'user', methods: ['GET'])]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $userList = $userRepository->findAll();
        $jsonUserList = $serializer->serialize($userList, 'json');

        return new JsonResponse($jsonUserList, Response::HTTP_OK, [], true);
    }
}
