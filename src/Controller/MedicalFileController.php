<?php

namespace App\Controller;

use App\Repository\MedicalFileRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MedicalFileController extends AbstractController
{
    #[Route('/api/medicalFiles', name: 'medicalFile', methods: ['GET'])]
    public function getAllMedicalFiles(MedicalFileRepository $medicalFileRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $medicalFileList = $medicalFileRepository->findAll();
        $jsonMedicalFileList = $serializer->serialize($medicalFileList, 'json');

        return new JsonResponse($jsonMedicalFileList, Response::HTTP_OK, [], true);
    }
}
