<?php

namespace App\Controller;

use App\Entity\MedicalFile;
use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatientController extends AbstractController
{
    #[Route('/api/patients', name: 'patient', methods: ['GET'])]
    public function getAllPatients(PatientRepository $patientRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $patientList = $patientRepository->findAll();
        $jsonPatientList = $serializer->serialize($patientList, 'json', ['groups' => 'getPatients']);

        return new JsonResponse($jsonPatientList, Response::HTTP_OK, [], true);
    }

    //Rechercher un patient
    #[Route('/api/patients/{id}', name: 'detailPatient', methods: ['GET'])]
    public function getDetailPatient(int $id, SerializerInterface $serializer,
    PatientRepository $patientRepository): JsonResponse {

        $patient = $patientRepository->find($id);
        if ($patient) {
            $jsonPatient = $serializer->serialize($patient, 'json', ['groups' => 'getPatients']);
            return new JsonResponse($jsonPatient, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer un patient
   #[Route('/api/patients/{id}', name: 'deletePatient', methods: ['DELETE'])]
    public function deletePatient(Patient $patient, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($patient);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer un patient
    #[Route('/api/patients', name: 'createPatient', methods: ['POST'])]
    public function createPatient(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $patient = $serializer->deserialize($request->getContent(), Patient::class, 'json');

        $newUserEmailAddress = $patient->getEmailAddress();
        $newUserGender = $patient->getGender();
        $newUserLastname = $patient->getLastName();
        //Vérification de l'existence d'une même adresse email dans la bdd
        $patientsWithSameEmail = $em->getRepository(Patient::class)->findBy(['emailAddress' => $newUserEmailAddress ]);
        if (count($patientsWithSameEmail) != 0) {
            return new JsonResponse(null, Response::HTTP_CONFLICT);
        } else {
            $medicalFile = new MedicalFile;
            $patient->setMedicalFile($medicalFile);
            $em->persist($medicalFile);
            $em->persist($patient);
            $em->flush();

            $to = $newUserEmailAddress;
                $subject = 'Bienvenue chez HEALTH NORTH';
                $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $file_path  = realpath('C:\Users\Gérald\Documents\BTS SIO\Projet\Projet1\API\health_north_api\public\accountCreationMail.txt');
            $message = file_get_contents($file_path);

            $message = str_replace('[lastName]', $newUserLastname, $message);
            $message = str_replace('[gender]', $newUserGender, $message );
            mail($to, $subject, $message, $headers);

            $jsonPatient = $serializer->serialize($patient, 'json', ['groups' => 'getPatients']);

            $location = $urlGenerator->generate('detailPatient', ['id' => $patient->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL);

            return new JsonResponse($jsonPatient, Response::HTTP_CREATED, ['Location' => $location], true);
        }
        
    }

    //Mise à jour 
    #[Route('/api/patients/{id}', name:"updatePatient", methods:['PUT'])]

    public function updatePatient(Request $request, SerializerInterface $serializer, 
    Patient $currentPatient, EntityManagerInterface $em): JsonResponse 
    {
        $updatePatient = $serializer->deserialize($request->getContent(),
                Patient::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentPatient]);

        $em->persist($updatePatient);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
