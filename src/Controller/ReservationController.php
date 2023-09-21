<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    #[Route('/api/reservations', name: 'reservation', methods: ['GET'])]
    public function getAllReservations(ReservationRepository $reservationRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $reservationList = $reservationRepository->findAll();
        $jsonReservationList = $serializer->serialize($reservationList, 'json');

        return new JsonResponse($jsonReservationList, Response::HTTP_OK, [], true);
    }
}
