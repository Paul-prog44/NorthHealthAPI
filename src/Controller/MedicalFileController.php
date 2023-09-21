<?php

namespace App\Controller;

use App\Entity\MedicalFile;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MedicalFileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicalFileController extends AbstractController
{
    #[Route('/api/medicalFiles', name: 'medicalFile', methods: ['GET'])]
    public function getAllMedicalFiles(MedicalFileRepository $medicalFileRepository, 
    SerializerInterface $serializer) :  JsonResponse
    {
        $medicalFileList = $medicalFileRepository->findAll();
        $jsonMedicalFileList = $serializer->serialize($medicalFileList, 'json');

        return new JsonResponse($jsonMedicalFileList, Response::HTTP_OK, [], true);
    }

    //Rechercher un dossier médical
    #[Route('/api/medicalFiles/{id}', name: 'detailMedicalFile', methods: ['GET'])]
    public function getDetailMedicalFile(int $id, SerializerInterface $serializer,
    MedicalFileRepository $medicalFileRepository): JsonResponse {

        $medicalFile = $medicalFileRepository->find($id);
        if ($medicalFile) {
            $jsonMedicalFile = $serializer->serialize($medicalFile, 'json');
            return new JsonResponse($jsonMedicalFile, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer un dossier médical
   #[Route('/api/medicalFiles/{id}', name: 'deleteMedicalFile', methods: ['DELETE'])]
    public function deleteMedicalFile(MedicalFile $medicalFile, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($medicalFile);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer un dossier médical
    #[Route('/api/medicalFiles', name: 'createMedicalFile', methods: ['POST'])]
    public function createMedicalFile(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $medicalFile = $serializer->deserialize($request->getContent(), MedicalFile::class, 'json');
        $em->persist($medicalFile);
        $em->flush();

        $jsonMedicalFile = $serializer->serialize($medicalFile, 'json');

        $location = $urlGenerator->generate('detailMedicalFile', ['id' => $medicalFile->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonMedicalFile, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    //Mise à jour 
    #[Route('/api/medicalFiles/{id}', name:"updateMedicalFile", methods:['PUT'])]

    public function updateMedicalFile(Request $request, SerializerInterface $serializer, 
    MedicalFile $currentMedicalFile, EntityManagerInterface $em): JsonResponse 
    {
        $updateMedicalFile = $serializer->deserialize($request->getContent(),
                MedicalFile::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentMedicalFile]);

        $em->persist($updateMedicalFile);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
