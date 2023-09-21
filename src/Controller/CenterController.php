<?php

namespace App\Controller;

use App\Repository\CenterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CenterController extends AbstractController
{
    #[Route('/api/centers', name: 'center', methods: ['GET'])]
    public function getAllCenters(CenterRepository $centerRepository) :  JsonResponse
    {
        $centerList = $centerRepository->findAll(); 

        return new JsonResponse([
            'centers' => $centerList,
        ]
        );
    }
}
