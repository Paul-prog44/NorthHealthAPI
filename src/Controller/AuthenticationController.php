<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationController extends AbstractController
{
    #[Route('/authentication', name: 'app_authentication', methods:['POST'])]
    public function authentication(Request $request, SerializerInterface $serializer): Response
    {
        $jsonRequest = $request->getContent();
        $dataArray = json_decode($jsonRequest, true);
        dd($dataArray['nom']);

        return $this->render('authentication/index.html.twig', [
            'controller_name' => 'AuthenticationController',
        ]);
    }
}
