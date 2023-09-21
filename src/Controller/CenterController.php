<?php

namespace App\Controller;

use App\Entity\Center;
use App\Repository\CenterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CenterController extends AbstractController
{
    #[Route('/api/centers', name: 'center', methods: ['GET'])]
    public function getAllCenters(CenterRepository $centerRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $centerList = $centerRepository->findAll();
        $jsonCenterList = $serializer->serialize($centerList, 'json');

        return new JsonResponse($jsonCenterList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/centers/{id}', name: 'detailCenter', methods: ['GET'])]
    public function getDetailCenter(int $id, SerializerInterface $serializer, CenterRepository $centerRepository): JsonResponse {

        $center = $centerRepository->find($id);
        if ($center) {
            $jsonCenter = $serializer->serialize($center, 'json');
            return new JsonResponse($jsonCenter, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   #[Route('/api/centers/{id}', name: 'deleteCenter', methods: ['DELETE'])]
    public function deleteCenter(Center $center, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($center);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/centers', name: 'createCenter', methods: ['POST'])]
    public function createCenter(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $center = $serializer->deserialize($request->getContent(), Center::class, 'json');
        $em->persist($center);
        $em->flush();

        $jsonCenter = $serializer->serialize($center, 'json');

        $location = $urlGenerator->generate('detailCenter', ['id' => $center->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCenter, Response::HTTP_CREATED, ['Location' => $location], true);
    }
}
