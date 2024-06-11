<?php

namespace App\Controller;

use mysqli;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


//Controlleur pour vérifier l'authentification de l'utilisateur de l'appli mobile qui renvoie l'objet user
class AuthenticationController extends AbstractController
{
    #[Route('/authentication', name: 'app_authentication', methods:['GET'])]
    public function authentication(Request $request): Response
    {
        $jsonRequest = $request->getContent();
        $credentials = json_decode($jsonRequest, true);

        $mysqli = new mysqli("127.0.0.1", "root", "", "north_health", 3306);
        // Vérification de la connexion
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $emailAddress = $mysqli->real_escape_string($credentials['emailAddress']);
        $sql = "SELECT * FROM patient WHERE email_address = '$emailAddress'";

        //Construction de la requête

        $result = $mysqli->query($sql);

        if ($result) {
            $allRows = $result->fetch_array(MYSQLI_ASSOC);
            dd($allRows["address"]);
            //Vérification du mot de passe
        }

        

        return $this->render('authentication/index.html.twig', [
            'controller_name' => 'AuthenticationController',
        ]);
    }
}
