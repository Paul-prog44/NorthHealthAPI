<?php

namespace App\Controller;

use App\Entity\Hospitalisation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HospitalisationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    //Rechercher une hospitalisation
    #[Route('/api/hospitalisations/{id}', name: 'detailhospitalisation', methods: ['GET'])]
    public function getDetailHospitalisation(int $id, SerializerInterface $serializer,
    HospitalisationRepository $hospitalisationRepository): JsonResponse {

        $hospitalisation = $hospitalisationRepository->find($id);
        if ($hospitalisation) {
            $jsonHospitalisation = $serializer->serialize($hospitalisation, 'json');
            return new JsonResponse($jsonHospitalisation, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer une hospitalisation
   #[Route('/api/hospitalisations/{id}', name: 'deleteHospitalisation', methods: ['DELETE'])]
    public function deleteHospitalisation(Hospitalisation $hospitalisation, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($hospitalisation);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer une hospitalisation
    #[Route('/api/hospitalisations', name: 'createHospitalisation', methods: ['POST'])]
    public function createHospitalisation(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $hospitalisation = $serializer->deserialize($request->getContent(), Hospitalisation::class, 'json');
        $em->persist($hospitalisation);
        $em->flush();

        $jsonHospitalisation = $serializer->serialize($hospitalisation, 'json');

        $location = $urlGenerator->generate('detailHospitalisation', ['id' => $hospitalisation->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonHospitalisation, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    //Mise à jour 
    #[Route('/api/hospitalisations/{id}', name:"updateHospitalisation", methods:['PUT'])]

    public function updateHospitalisation(Request $request, SerializerInterface $serializer, 
    Hospitalisation $currentHospitalisation, EntityManagerInterface $em): JsonResponse 
    {
        $updateHospitalisation = $serializer->deserialize($request->getContent(),
                Hospitalisation::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentHospitalisation]);

        $em->persist($updateHospitalisation);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
