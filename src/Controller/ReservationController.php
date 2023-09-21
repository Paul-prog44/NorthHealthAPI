<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/api/reservations', name: 'reservation', methods: ['GET'])]
    public function getAllReservations(ReservationRepository $reservationRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $reservationList = $reservationRepository->findAll();
        $jsonReservationList = $serializer->serialize($reservationList, 'json');

        return new JsonResponse($jsonReservationList, Response::HTTP_OK, [], true);
    }

    //Rechercher une réservation
    #[Route('/api/reservations/{id}', name: 'detailReservation', methods: ['GET'])]
    public function getDetailReservation(int $id, SerializerInterface $serializer,
    ReservationRepository $reservationRepository): JsonResponse {

        $reservation = $reservationRepository->find($id);
        if ($reservation) {
            $jsonReservation = $serializer->serialize($reservation, 'json');
            return new JsonResponse($jsonReservation, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer une réservation
   #[Route('/api/reservations/{id}', name: 'deleteReservation', methods: ['DELETE'])]
    public function deleteReservation(Reservation $reservation, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($reservation);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer une réservation
    #[Route('/api/reservations', name: 'createReservation', methods: ['POST'])]
    public function createReservation(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $reservation = $serializer->deserialize($request->getContent(), Reservation::class, 'json');
        $em->persist($reservation);
        $em->flush();

        $jsonReservation = $serializer->serialize($reservation, 'json');

        $location = $urlGenerator->generate('detailReservation', ['id' => $reservation->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonReservation, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    //Mise à jour
    #[Route('/api/reservations/{id}', name:"updateReservation", methods:['PUT'])]

    public function updateReservation(Request $request, SerializerInterface $serializer, 
    Reservation $currentReservation, EntityManagerInterface $em): JsonResponse 
    {
        $updateReservation = $serializer->deserialize($request->getContent(),
                Reservation::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentReservation]);

        $em->persist($updateReservation);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
