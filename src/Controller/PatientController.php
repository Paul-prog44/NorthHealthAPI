<?php

namespace App\Controller;

use App\Repository\PatientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends AbstractController
{
    #[Route('/api/patients', name: 'patient', methods: ['GET'])]
    public function getAllPatients(PatientRepository $patientRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $patientList = $patientRepository->findAll();
        $jsonPatientList = $serializer->serialize($patientList, 'json');

        return new JsonResponse($jsonPatientList, Response::HTTP_OK, [], true);
    }
}
