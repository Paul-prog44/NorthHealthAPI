<?php

namespace App\Controller;

use mysqli;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PatientRepository;
use Symfony\Component\Serializer\SerializerInterface;




//Controlleur pour vérifier l'authentification de l'utilisateur de l'appli mobile qui renvoie l'objet patient
class AuthenticationController extends AbstractController
{
    #[Route('/authentication', name: 'app_authentication', methods:['POST'])]
    public function authentication(Request $request, PatientRepository $patientRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonRequest = $request->getContent();

        //Transforme le json en tableau associatif
        $credentials = json_decode($jsonRequest, true);
        

        $mysqli = new mysqli("127.0.0.1", "root", "", "north_health", 3306);
        // Vérification de la connexion
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        //Construction de la requête
        $emailAddress = $mysqli->real_escape_string($credentials['emailAddress']);
        $sql = "SELECT * FROM patient WHERE email_address = '$emailAddress'";

        $result = $mysqli->query($sql);
        $allRows = $result->fetch_array(MYSQLI_ASSOC);
        
        if ($allRows) {
            //Vérification du mot de passe
            if (password_verify( $credentials["password"], $allRows["password"])) {
                $id = $allRows["id"];
                $patient = $patientRepository->find($id);

                $jsonPatient = $serializer->serialize($patient, 'json', ['groups' => 'getPatients']);
                return new JsonResponse($jsonPatient, Response::HTTP_OK, [], true);
            } else {
                return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
            }
        }

        return new JsonResponse(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
