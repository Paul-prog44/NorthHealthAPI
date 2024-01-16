<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Center;
use App\Entity\Doctor;
use App\Entity\Hospitalisation;
use App\Entity\MedicalFile;
use App\Entity\Patient;
use App\Entity\Reservation;
use App\Entity\Specialty;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    //Génération de date aléatoire
    private function generateRandomDate()
    {
        $dateDebut = new \DateTime('2023-01-01');
        $dateFin = new \DateTime('2025-12-31');

        $timestampDebut = $dateDebut->getTimestamp();
        $timestampFin = $dateFin->getTimestamp();

        // Générez un timestamp aléatoire entre la date de début et la date de fin
        $timestampAleatoire = mt_rand($timestampDebut, $timestampFin);

        // Convertissez le timestamp en objet DateTime
        $dateAleatoire = new \DateTime();
        $dateAleatoire->setTimestamp($timestampAleatoire);

        return $dateAleatoire;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@healthnorthapi.com');
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $userAdmin = new User();
        $userAdmin->setEmail('admin@healthnorthapi.com');
        $userAdmin->setRoles(['ROLES_ADMIN']);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, 'password'));
        $manager->persist($userAdmin);

        //Valeurs élatoires pour hydrater les objets
        $genders = ["M", "Mme", "Mlle"];
        $boolean = [true, false];
        $allergies = ["aucune", "acariens ", "animaux domestiques", "pollens", "moisissures",
        "poissons", "lactose", "arachide", "gluten"];
        $medicalSpecialties = [ "allergologie ", "pathologique ", "gériatrie", "biologie",
        "cardiologie", "chirurgie", "dentaire", "dermatologie", "podologie", "ophtalmologie", "gynécologie"];

        


        $medicaleFilesArray = [];
        $doctorsArray = [];
        $centersArray = [];
        $hospitalisationsArray = [];

        for ($i=0; $i<5; $i++)
        {
            //Création des hospitalisations
            $hospitalisation = new Hospitalisation();
            $hospitalisation->settype("TypeD'Intervention".$i);
            $hospitalisation->setVegetarian($boolean[array_rand($boolean)]);
            $hospitalisation->setSingleRoom($boolean[array_rand($boolean)]);
            $hospitalisation->setTelevision($boolean[array_rand($boolean)]);
            $manager->persist($hospitalisation);

            $hospitalisationsArray[] = $hospitalisation;

        }

        for ($i=0; $i<20; $i++)
        {
            //Création des fichiers médicaux
            $medicalFile = new MedicalFile();
            $medicalFile->setAllergies($allergies[array_rand($allergies)]);
            $medicalFile->setDocuments("Document ".$i);
            $manager->persist($medicalFile);

            $medicaleFilesArray[]= $medicalFile;

            //Création des patients
            $patient = new Patient();
            $patient->setGender($genders[array_rand($genders)]);
            $patient->setLastName("Nom de famille".$i);
            $patient->setFirstName("Prénom". $i);
            $patient->setAddress("Adresse".$i);
            $patient->setEmailAddress("randommail".$i."@anonymous.com");
            $patient->setPassword('password'.$i);
            $patient->setSocialSecurity('0123456789'.$i);
            $patient->setMedicalFile($medicalFile);
            $manager->persist($patient);

        }

        for ($i=0; $i<10; $i++)
        {

            //Création des spécialités
            $specialty = new Specialty();
            $specialty->setName($medicalSpecialties[array_rand($medicalSpecialties)]);
            $manager->persist($specialty);

            $specialty2 = new Specialty();
            $specialty2->setName($medicalSpecialties[array_rand($medicalSpecialties)]);
            $manager->persist($specialty2);

            $specialty3 = new Specialty();
            $specialty3->setName($medicalSpecialties[array_rand($medicalSpecialties)]);
            $manager->persist($specialty3);

            $specialty4 = new Specialty();
            $specialty4->setName($medicalSpecialties[array_rand($medicalSpecialties)]);
            $manager->persist($specialty4);

            //Création des centres
            $center = new Center();
            $center->setName("Nom du centre ".$i);
            $center->setCity("Ville ".$i);
            $center->setCountry("Pays ".$i);
            $center->addSpecialty($specialty);
            $center->addSpecialty($specialty2);
            $center->addSpecialty($specialty3);
            $center->addSpecialty($specialty4);
            $center->setAddress("Adresse du centre n° ".$i);
            $manager->persist($center);

            $centersArray[] = $center;

            //Création des docteurs

            $doctorsArray = [];

            for ($j = 0; $j<5 ; $j++ ) {
                $doctor[$j] = new Doctor();
                $doctor[$j]->setGender($genders[array_rand($genders)]);
                $doctor[$j]->setFirstName("PrénomDocteur ".$i.$j);
                $doctor[$j]->setLastname("NomDocteur ".$i.$j);
                $doctor[$j]->setCenter($centersArray[array_rand($centersArray)]);
                $doctor[$j]->addSpecialty($specialty);
                $manager->persist($doctor[$j]);

                $doctorsArray[]= $doctor[$j];
            }



            //Création des réservations
            $reservation = new Reservation();
            $randomDate = $this->generateRandomDate();
            $reservation->setDate($randomDate);
            $reservation->setMedicalFile($medicaleFilesArray[array_rand($medicaleFilesArray)]);
            $reservation->setDoctor($doctorsArray[array_rand($doctorsArray)]);
            $reservation->setCenter($centersArray[array_rand($centersArray)]);
            // $reservation->setHospitalisation($hospitalisationsArray[array_rand($hospitalisationsArray)]);
            $manager->persist($reservation);


        }

        

        $manager->flush();


    }
}
