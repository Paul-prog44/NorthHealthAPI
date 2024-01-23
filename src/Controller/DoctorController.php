<?php

namespace App\Controller;

use App\Entity\Center;
use App\Entity\Doctor;
use App\Entity\Specialty;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DoctorController extends AbstractController
{
    #[Route('/api/doctors', name: 'doctor', methods: ['GET'])]
    public function getAllDoctors(DoctorRepository $doctorRepository, SerializerInterface $serializer) :  JsonResponse
    {
        $doctorList = $doctorRepository->findAll();
        $jsonDoctorList = $serializer->serialize($doctorList, 'json', ['groups' => 'getDoctors']);

        return new JsonResponse($jsonDoctorList, Response::HTTP_OK, [], true);
    }

    //Rechercher un docteur
    #[Route('/api/doctors/{id}', name: 'detailDoctor', methods: ['GET'])]
    public function getDetailDoctor(int $id, SerializerInterface $serializer,
    DoctorRepository $doctorRepository): JsonResponse {

        $doctor = $doctorRepository->find($id);
        if ($doctor) {
            $jsonDoctor = $serializer->serialize($doctor, 'json', ['groups' => 'getDoctors']);
            return new JsonResponse($jsonDoctor, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
   }

   //Effacer un docteur
   #[Route('/api/doctors/{id}', name: 'deleteDoctor', methods: ['DELETE'])]
    public function deleteDoctor(Doctor $doctor, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($doctor);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    //Créer un docteur
    #[Route('/api/doctors', name: 'createDoctor', methods: ['POST'])]
    public function createDoctor(Request $request, SerializerInterface $serializer,
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $requestArray = $request->toArray(); //transforme le JSON de la requête en tableau associatif
        $doctor = $serializer->deserialize($request->getContent(), Doctor::class, 'json');
        $center = $em->getRepository(Center::class)->find($requestArray["centerId"]);
        $specialty = $em->getRepository(Specialty::class)->find($requestArray["specialtyId"]);
        $doctor->setCenter($center);
        $doctor->addSpecialty($specialty);
        
        $em->persist($doctor);
        $em->flush();

        $jsonDoctor = $serializer->serialize($doctor, 'json', ['groups' => "getDoctors"]);

        $location = $urlGenerator->generate('detailDoctor', ['id' => $doctor->getId()],
        UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonDoctor, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    //Mise à jour 
    #[Route('/api/doctors/{id}', name:"updateDoctor", methods:['PUT'])]

    public function updateDoctor(Request $request, SerializerInterface $serializer, 
    Doctor $currentDoctor, EntityManagerInterface $em): JsonResponse 
    {
        $updateDoctor = $serializer->deserialize($request->getContent(),
                Doctor::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentDoctor]);

        $em->persist($updateDoctor);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
