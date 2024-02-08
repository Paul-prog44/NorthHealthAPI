<?php

namespace App\Controller;

use App\Entity\Center;
use App\Entity\Specialty;
use App\Repository\CenterRepository;
use App\Repository\SpecialtyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CenterController extends AbstractController
{
    //Rechercher tous les centres
    #[Route('/api/centers', name: 'center', methods: ['GET'])]
    public function getAllCenters(CenterRepository $centerRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $centerList = $centerRepository->findAll();
        $jsonCenterList = $serializer->serialize($centerList, 'json', ['groups' => 'getCenters']);
        //Conversion Objet en JSON = serialize + assignation group pour éviter ref circulaire

        return new JsonResponse($jsonCenterList, Response::HTTP_OK, [], true); 
    }

    //Rechercher un centre
    #[Route('/api/centers/{id}', name: 'detailCenter', methods: ['GET'])]
    public function getDetailCenter(int $id, SerializerInterface $serializer,
    CenterRepository $centerRepository): JsonResponse {

        $center = $centerRepository->find($id);
        if ($center) {
            $jsonCenter = $serializer->serialize($center, 'json', ['groups' => 'getCenters']);
            return new JsonResponse($jsonCenter, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer un centre
   #[Route('/api/centers/{id}', name: 'deleteCenter', methods: ['DELETE'])]
    public function deleteCenter(Center $center, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($center);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer un centre
    #[Route('/api/centers', name: 'createCenter', methods: ['POST'])]
    public function createCenter(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $requestArray = $request->toArray(); //transforme le JSON de la requête en tableau associatif
        $specialtiesArray =  $requestArray['specialtiesArray'];
        $center = $serializer->deserialize($request->getContent(), Center::class, 'json');


        foreach ($specialtiesArray as $specialty)
        {
        $specialtyObject = $em->getRepository(Specialty::class)->find($specialty);
        $center->addSpecialty($specialtyObject);
        }


        $em->persist($center);
        $em->flush();

        $jsonCenter = $serializer->serialize($center, 'json',  ['groups' => "getCenters"]); //définition du contexte pour éviter les réf circ !!!

        $location = $urlGenerator->generate('detailCenter', ['id' => $center->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCenter, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    //Mise à jour 
    #[Route('/api/centers/{id}', name:"updateCenter", methods:['PUT'])]

    public function updateCenter(Request $request, SerializerInterface $serializer, 
    Center $currentCenter, EntityManagerInterface $em): JsonResponse 
    {
        $jsonCurrentCenter = $serializer->serialize($currentCenter, 'json', ['groups' => 'getCenters']);
        $currentCenterArray = json_decode($jsonCurrentCenter, true);

        //Mise dans un tableau des id des spécialités avant MAJ
        $currentSpecialties = [];
        foreach ($currentCenterArray['specialties'] as $currentSpecialty)
        {
            $currentSpecialties[] = $currentSpecialty['id'];
        }


        $requestArray = $request->toArray(); //transforme le JSON de la requête en tableau associatif
        $specialtiesArray =  $requestArray['specialtiesArray'];
        $updateCenter = $serializer->deserialize($request->getContent(),
                Center::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCenter]);

        //Actualise les spécialités
        foreach ($currentSpecialties as $specialty)
        {
            if (!in_array($specialty, $specialtiesArray))
            {
                $specialtyObject = $em->getRepository(Specialty::class)->find($specialty);
                $updateCenter->removeSpecialty($specialtyObject);
            }
        }
        foreach ($specialtiesArray as $specialty)
            {
                $specialtyObject = $em->getRepository(Specialty::class)->find($specialty);
                $updateCenter->addSpecialty($specialtyObject);
            }

        $em->persist($updateCenter);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
