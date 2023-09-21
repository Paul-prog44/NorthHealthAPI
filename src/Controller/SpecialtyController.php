<?php

namespace App\Controller;

use App\Repository\SpecialtyRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SpecialtyController extends AbstractController
{
    #[Route('/api/specialtys', name: 'specialty', methods: ['GET'])]
    public function getAllSpecialtys(SpecialtyRepository $specialtyRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $specialtyList = $specialtyRepository->findAll();
        $jsonSpecialtyList = $serializer->serialize($specialtyList, 'json');

        return new JsonResponse($jsonSpecialtyList, Response::HTTP_OK, [], true);
    }
}
