<?php

namespace App\Controller;

use App\Repository\GenderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GenderController extends AbstractController
{
    #[Route('/api/genders', name: 'genders',  methods: ['GET'])]
    public function getGenders(GenderRepository $genderRepository, SerializerInterface $serializer) : JsonResponse
    {
        $genderList = $genderRepository->findAll();
        $jsonGenderList = $serializer->serialize($genderList, 'json');

        return new JsonResponse($jsonGenderList, Response::HTTP_OK, [], true);
    }
}
