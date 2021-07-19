<?php

namespace App\Controller;

use App\Repository\EmprunteurRepository;
use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use App\Repository\UserRepository;
use App\Repository\AuteurRepository;
use App\Repository\GenreRepository;
use App\Entity\Livre;
use App\Entity\Emprunt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(  

        EmprunteurRepository $emprunteurRepository,
        EmpruntRepository $empruntRepository,
        LivreRepository $livreRepository,
        UserRepository $userRepository,
        AuteurRepository $auteurRepository,
        GenreRepository $genreRepository): Response

    {
    // return $this->render('test/index.html.twig', [
        //  'controller_name' => 'TestController',
        // ]);

        $entityManager = $this->getDoctrine()->getManager();

//Les utilisateurs

        //la liste complète de tous les utilisateurs (de la table `user`)
         $users = $userRepository->findAll();
         dump($users);

        //les données de l'utilisateur dont l'id est `1`
         $user = $userRepository->findOneById(1);
         dump($user);

        //- les données de l'utilisateur dont l'email est `foo.foo@example.com`
         $user = $userRepository->findByEmail('foo.foo@example.com');
         dump($user);

        //les données des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_EMRUNTEUR`
         $usersRole = $userRepository->findByRole('ROLE_EMPRUNTEUR');
         dump($usersRole);

//Les livres       

        //la liste complète de tous les livres
         $livres = $livreRepository->findAll();
         dump($livres);

        //les données du livre dont l'id est `1`
         $livre = $livreRepository->findById(1);
         dump($livre);

        //la liste des livres dont le titre contient le mot clé `lorem`
         $livres = $livreRepository->findByTitle('lorem');
         dump($livres);

        //la liste des livres dont l'id de l'auteur est `2`
         $livre = $livreRepository->findByAuteurId(2);
         dump($livre);

        //la liste des livres dont le genre contient le mot clé `roman`
         $livres = $livreRepository->findByGenre('roman');
         dump($livres);

//Requêtes de création :   

         $auteurs = $auteurRepository->findAll();
         $genres = $genreRepository->findAll();

         $livre = new Livre();
         $livre->setTitre('Totum autem id externum');
         $livre->setAnneeEdition(2020);
         $livre->setNombrePages(300);
         $livre->setCodeIsbn('9790412882714');
         $livre->setAuteur($auteurs[2]);
         $livre->addGenre($genres[6]);

         $entityManager->flush();
         dump($livre);

//Requêtes de mise à jour :

         $genres = $genreRepository->findAll();

         $livre = $livreRepository->findById(2)[0];
         dump($livre);

         $livre->setTitre('Aperiendum est igitur');
         $livre->removeGenre($genres[2]); // nouvelle
         $livre->addGenre($genres[5]); // roman d'aventure

         $entityManager->flush();
         dump($livre);

//Requêtes de suppression :

         $livre = $livreRepository->findById(123)[0];
         dump($livre);

         $entityManager->remove($livre);
         $entityManager->flush();
         dump($livre); 

// Les emprunteurs


        
        //la liste complète des emprunteurs
         $emprunteur = $emprunteurRepository->findAll();
         dump($emprunteur);

        //les données de l'emprunteur dont l'id est `3`
         $emprunteur = $emprunteurRepository->findById(3);
         dump($emprunteur);

        //les données de l'emprunteur qui est relié au user dont l'id est `3`
         $emprunteur = $emprunteurRepository->findByUserId(3);
         dump($emprunteur);

        // la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`
         $emprunteurs = $emprunteurRepository->findByFirstnameOrLastname('foo');
         dump($emprunteurs);

        // la liste des emprunteurs dont le téléphone contient le mot clé `1234`
         $emprunteurs = $emprunteurRepository->findByTel('1234');
         dump($emprunteurs);

        //la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit)
         $emprunteurs = $emprunteurRepository->findByCreationDate('2021-03-01 00:00:00');
         dump($emprunteurs);

        // la liste des emprunteurs inactifs (c-à-d dont l'attribut `actif` est égal à `false`)
         $emprunteurs = $emprunteurRepository->findByStatus(false);
         dump($emprunteurs);

//Les emprunts

        //la liste des 10 derniers emprunts au niveau chronologique
         $emprunts = $empruntRepository->findLastTen();
         dump($emprunts);

        //la liste des emprunts de l'emprunteur dont l'id est `2`
         $emprunts = $empruntRepository->findByEmprunteurId(2);
         dump($emprunts);

        //la liste des emprunts du livre dont l'id est `3`
         $emprunts = $empruntRepository->findByLivreId(3);
         dump($emprunts);

        //la liste des emprunts qui ont été retournés avant le 01/01/2021
         $emprunts = $empruntRepository->findByDateRetour('2021-01-01 00:00:00');
         dump($emprunts);

        //la liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle)
         $emprunts = $empruntRepository->findEmpruntsNonRendus();
         dump($emprunts);

        //les données de l'emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés (c-à-d dont la date de retour est nulle)
         $emprunt = $empruntRepository->findOneByLivreIdAndDateRetour(3);
         dump($emprunt);

//Requêtes de création :
        
        $emprunteurs = $emprunteurRepository->findAll();
        $livres = $livreRepository->findAll();

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
        $emprunt->setDateRetour(NULL);
        $emprunt->setEmprunteur($emprunteurs[0]);
        $emprunt->setLivre($livres[0]);

        $entityManager->flush();
        dump($emprunt);

//Requêtes de mise à jour :

        $emprunt = $empruntRepository->findById(3)[0];
        dump($emprunt);

        $emprunt->setDateRetour(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));

        $entityManager->flush();
        dump($emprunt);

//Requêtes de suppression :

         $emprunt = $empruntRepository->findById(42)[0];
         dump($emprunt);

         $entityManager->remove($emprunt);
         $entityManager->flush();
         dump($emprunt);

        exit();
    }
}
   