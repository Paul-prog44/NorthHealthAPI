<?php

namespace App\Controller;

use App\Repository\CenterRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CenterController extends AbstractController
{
    #[Route('/api/centers', name: 'center', methods: ['GET'])]
    public function getAllCenters(CenterRepository $centerRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $centerList = $centerRepository->findAll();
        $jsonCenterList = $serializer->serialize($centerList, 'json');

        return new JsonResponse($jsonCenterList, Response::HTTP_OK, [], true);
    }
}
