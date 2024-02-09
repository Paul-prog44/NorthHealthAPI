<?php

namespace App\Controller;

use App\Entity\Center;
use App\Entity\Specialty;
use App\Repository\SpecialtyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SpecialtyController extends AbstractController
{
    #[Route('/api/specialties', name: 'specialty', methods: ['GET'])]
    public function getAllSpecialtys(SpecialtyRepository $specialtyRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $specialtyList = $specialtyRepository->findAll();
        $jsonSpecialtyList = $serializer->serialize($specialtyList, 'json', ['groups' => 'getSpecialties']);

        return new JsonResponse($jsonSpecialtyList, Response::HTTP_OK, [], true);
    }

    //Rechercher une spécialité
    #[Route('/api/specialties/{id}', name: 'detailSpecialty', methods: ['GET'])]
    public function getDetailSpecialty(int $id, SerializerInterface $serializer,
    SpecialtyRepository $specialtyRepository): JsonResponse {

        $specialty = $specialtyRepository->find($id);
        if ($specialty) {
            $jsonSpecialty = $serializer->serialize($specialty, 'json', ['groups' => 'getSpecialties']);
            return new JsonResponse($jsonSpecialty, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer une spécialité
   #[Route('/api/specialties/{id}', name: 'deleteSpecialty', methods: ['DELETE'])]
    public function deleteSpecialty(Specialty $specialty, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($specialty);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer une spécialité
    #[Route('/api/specialties', name: 'createSpecialty', methods: ['POST'])]
    public function createSpecialty(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $requestArray = $request->toArray();
        $centerId = $requestArray['centerId'];

        $specialty = $serializer->deserialize($request->getContent(), Specialty::class, 'json');
        //Associe un centre à une spécialité
        $centerObject = $em->getRepository(Center::class)->find($centerId);
        $specialty->addCenter($centerObject);

        $em->persist($specialty);
        $em->flush();

        $jsonSpecialty = $serializer->serialize($specialty, 'json', ['groups' => "getSpecialties"]);

        $location = $urlGenerator->generate('detailSpecialty', ['id' => $specialty->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonSpecialty, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    //Mise à jour 
    #[Route('/api/specialties/{id}', name:"updateSpecialty", methods:['PUT'])]

    public function updateSpecialty(Request $request, SerializerInterface $serializer, 
    Specialty $currentSpecialty, EntityManagerInterface $em): JsonResponse 
    {
        $updateSpecialty = $serializer->deserialize($request->getContent(),
                Specialty::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentSpecialty]);

        $em->persist($updateSpecialty);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
