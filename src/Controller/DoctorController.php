<?php

namespace App\Controller;

use App\Repository\DoctorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DoctorController extends AbstractController
{
    #[Route('/api/doctors', name: 'doctor', methods: ['GET'])]
    public function getAllDoctors(DoctorRepository $doctorRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $doctorList = $doctorRepository->findAll();
        $jsonDoctorList = $serializer->serialize($doctorList, 'json');

        return new JsonResponse($jsonDoctorList, Response::HTTP_OK, [], true);
    }
}
