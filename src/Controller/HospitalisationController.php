<?php

namespace App\Controller;

use App\Repository\HospitalisationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HospitalisationController extends AbstractController
{
    #[Route('/api/hospitalisations', name: 'hospitalisation', methods: ['GET'])]
    public function getAllHospitalisations(HospitalisationRepository $hospitalisationRepository,
    SerializerInterface $serializer) :  JsonResponse
    {
        $hospitalisationList = $hospitalisationRepository->findAll();
        $jsonHospitalisationList = $serializer->serialize($hospitalisationList, 'json');

        return new JsonResponse($jsonHospitalisationList, Response::HTTP_OK, [], true);
    }
}
