<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = FakerFactory::create('fr_FR');
    }

    public static function getGroups(): array
    {
        // Cette fixture fait partie du groupe "test".
        // Cela permet de cibler seulement certains fixtures
        // quand on exécute la commande doctrine:fixtures:load.
        // Pour que la méthode statique getGroups() soit prise
        // en compte, il faut que la classe implémente
        // l'interface FixtureGroupInterface.
        return ['test'];
    }


    public function load(ObjectManager $manager)
    {
        $auteursCount = 500;
        $emprunteursCount = 100;
        $empruntsCount = 200;
        $livresCount = 1000;

        $admins = $this->loadAdmin($manager);
        $auteurs = $this->loadAuteur($manager, $auteursCount);
        $genres = $this->loadGenre($manager);
        $emprunteurs = $this->loadEmprunteur($manager, $emprunteursCount);
        $emprunts = $this->loadEmprunt($manager, $emprunteurs, $empruntsCount);
        $livres = $this->loadLivre($manager, $auteurs, $genres, $emprunts, $livresCount);
        $manager->flush();
    }
    
    public function loadAdmin(ObjectManager $manager)
    {
        $admins = [];
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $password = $this->encoder->encodePassword($admin, '123');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $admins[] = $admin;
    }

    public function loadAuteur(ObjectManager $manager, int $count)
    {
        $auteurs = [];
        $auteur = new Auteur();
        $auteur->setNom('auteur');
        $auteur->setPrenom('inconnu');
        $manager->persist($auteur);
        $auteurs[] = $auteur;


        $auteur = new auteur();
        $auteur->setNom('Cartier');
        $auteur->setPrenom('Hugues');
        $manager->persist($auteur);
        $auteurs[] = $auteur;
        
        $auteur = new auteur();
        $auteur->setNom('Lambert');
        $auteur->setPrenom('Armand');
        $manager->persist($auteur);
        $auteurs[] = $auteur;


        $auteur = new auteur();
        $auteur->setNom('Moitessier');
        $auteur->setPrenom('Thomas');
        $manager->persist($auteur);
        $auteurs[] = $auteur;

        // // création de school years avec des données aléatoires
        // for ($i = 1; $i < $count; $i++) {
        //     $auteurs = [];
        //     $auteur = new Auteur();
        //     $auteur->setNom('auteur');
        //     $auteur->setPrenom('inconnu');

        //     $manager->persist($auteur);
        //     $auteurs[] = $auteur;
        // }

        for($i = 4; $i < $count; $i++) {
            $auteur = new Auteur();
            $auteur->setNom($this->faker->lastname());
            $auteur->setPrenom($this->faker->firstname());

            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }
        return $auteurs;
    }

    public function loadEmprunt(ObjectManager $manager, Array $emprunteursParam, int $count)
    {
        $emprunts = [];
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'));
        $emprunt->setDateRetour(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'));
        $emprunt->setEmprunteur($emprunteursParam[0]);

        $manager->persist($emprunt);
        $emprunts[] = $emprunt;

        for($i = 1; $i < $count; $i++) {
           // if($i%2 === 0) {
                $emprunteurIndex = $i/2;
                $emprunt = new Emprunt();
                $emprunt->setDateEmprunt($this->faker->dateTimeThisYear($max = 'now', $timezone =null));
                $emprunt->setDateRetour($this->faker->dateTimeThisYear($max = 'now', $timezone =null));
                $emprunt->setEmprunteur($emprunteursParam[$emprunteurIndex]);
                $manager->persist($emprunt);
                $emprunts[] = $emprunt;
            // } else {

            //     $emprunteurIndex = ($i-1)/2;
            //     $emprunt = new Emprunt();
            //     $emprunt->setDateEmprunt($this->faker->dateTimeThisYear($max = 'now', $timezone =null));
            //     $emprunt->setDateRetour($this->faker->dateTimeThisYear($max = 'now', $timezone =null));
            //     $emprunt->setEmprunteur($emprunteursParam[$emprunteurIndex]);
            //     $manager->persist($emprunt);
            //     $emprunts[] = $emprunt;
           
        }
        return $emprunts;
    }

    public function loadEmprunteur(ObjectManager $manager, int $count)
        {
            $emprunteurs = [];
            $user = new User();
            $user->setEmail('foo.foo@example.com');
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_EMPRUNTEUR']);
            $manager->persist($user);

            $emprunteur = new Emprunteur();
            $emprunteur->setNom('foo');
            $emprunteur->setPrenom('foo');
            $emprunteur->setTel('123456789');
            $emprunteur->setActif(true);
            $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'));
            $emprunteur->setUser($user);

            $manager->persist($emprunteur);
            $emprunteurs[] = $emprunteur;

            $user = new User();
            $user->setEmail('bar.bar@example.com');
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_EMPRUNTEUR']);
            $manager->persist($user);

            $emprunteur = new Emprunteur();
            $emprunteur->setNom('bar');
            $emprunteur->setPrenom('bar');
            $emprunteur->setTel('123456789');
            $emprunteur->setActif(true);
            $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'));
            $emprunteur->setUser($user);

            $manager->persist($emprunteur);
            $emprunteurs[] = $emprunteur;

            $user = new User();
            $user->setEmail('baz.baz@example.com');
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_EMPRUNTEUR']);

            $manager->persist($user);


            $emprunteur = new Emprunteur();
            $emprunteur->setNom('baz');
            $emprunteur->setPrenom('baz');
            $emprunteur->setTel('123456789');
            $emprunteur->setActif(true);
            $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'));
            $emprunteur->setUser($user);

            $manager->persist($emprunteur);
            $emprunteurs[] = $emprunteur;

            for($i = 3; $i < $count; $i++) {

                $user = new User();
                $user->setEmail($this->faker->email());
                $password = $this->encoder->encodePassword($user, '123');
                $user->setPassword($password);
                $user->setRoles(['ROLE_EMPRUNTEUR']);

                $manager->persist($user);
                $emprunteur = new Emprunteur();
                $emprunteur->setNom($this->faker->lastname());
                $emprunteur->setPrenom($this->faker->firstname());
                $emprunteur->setTel($this->faker->phoneNumber());
                $emprunteur->setActif($this->faker->boolean());
                $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:00:00'));
                $emprunteur->setUser($user);

                $manager->persist($emprunteur);
                // on ajoute chaque emprunter créée
                $emprunteurs[] = $emprunteur;
            }
        // Renvoi de la liste des emprunteurs créées.
        return $emprunteurs;
        }
        public function loadGenre(ObjectManager $manager)
        {
            $genres = [];
            $genre = new Genre();
            $genre->setNom('biographie');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('conte');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('essai');
            $manager->persist($genre);
            $genres[] = $genre;


            $genre = new Genre();
            $genre->setNom('fantasy');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('journal intime');
            $manager->persist($genre);
            $genres[] = $genre;


            $genre = new Genre();
            $genre->setNom('nouvelle');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('poésie');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom("roman d'amour");
            $manager->persist($genre);
            $genres[] = $genre;


            $genre = new Genre();
            $genre->setNom("roman d'aventure");
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('roman historique');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('science-fiction');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('témoignage');
            $manager->persist($genre);
            $genres[] = $genre;

            $genre = new Genre();
            $genre->setNom('théâtre');
            $manager->persist($genre);
            $genres[] = $genre;

            return $genres;
        }

        public function loadLivre(ObjectManager $manager, $auteursParam, $genresParam, $empruntsParam, $livresCount)
        {
            $auteur = $auteursParam[0];
            $genre = $genresParam[0];
            $emprunt = array_shift($empruntsParam);

            $livre = [];
            $livre = new livre();
            $livre->setTitre('Lorem ipsum dolor sit amet');
            $livre->setAnneeEdition(2010);
            $livre->setNombrePages(100);
            $livre->setCodeIsbn('9785786930024');
            $livre->setAuteur($auteur);
            $livre->addGenre($genre);
            $livre->addEmprunt($emprunt);

            $manager->persist($livre);
            $livres[] = $livre;

            $auteur = $auteursParam[1];
            $genre = $genresParam[1];
            $emprunt = array_shift($empruntsParam);
            

            $livre = new livre();
            $livre->setTitre('Consectetur adipiscing elit ');
            $livre->setAnneeEdition(2011);
            $livre->setNombrePages(150);
            $livre->setCodeIsbn('9783817260935 ');
            $livre->setAuteur($auteur);
            $livre->addGenre($genre);
            $livre->addEmprunt($emprunt);

            $manager->persist($livre);
            $livres[] = $livre;

            $auteur = $auteursParam[2];
            $genre = $genresParam[2];
            $emprunt = array_shift($empruntsParam);

            $livre = new livre();
            $livre->setTitre('Mihi quidem Antiochum ');
            $livre->setAnneeEdition(2012);
            $livre->setNombrePages(200);
            $livre->setCodeIsbn('9782020493727');
            $livre->setAuteur($auteur);
            $livre->addGenre($genre);
            $livre->addEmprunt($emprunt);

            $manager->persist($livre);
            $livres[] = $livre;

            $auteur = $auteursParam[3];
            $genre = $genresParam[3];
            $emprunt = array_shift($empruntsParam);

            $livre = new livre();
            $livre->setTitre('Quem audis satis belle');
            $livre->setAnneeEdition(2013);
            $livre->setNombrePages(250);
            $livre->setCodeIsbn('9782020493727');
            $livre->setAuteur($auteur);
            $livre->addGenre($genre);
            $livre->addEmprunt($emprunt);

            $manager->persist($livre);
            $livres[] = $livre;

            // création de livre avec des données aléatoires
            for ($i = 4; $i < $livresCount; $i++) {
                if($i%2 === 0) {
                    $auteurIndex = $i/2;
                } else {
                    $auteurIndex = ($i-1)/2;
                }


                $livre = new Livre();
                $livre->setTitre($this->faker->sentence(2));
                $livre->setAnneeEdition($this->faker->numberBetween($min = 1950, $max = 2021));
                $livre->setNombrePages($this->faker->numberBetween($min = 30, $max = 1000));
                $livre->setCodeIsbn($this->faker->ean13());
                $livre->setAuteur($auteursParam[$auteurIndex]);
                $livre->addGenre($genresParam[$this->faker->numberBetween($min = 0, $max = 12)]);
                
                if (!empty($empruntsParam)){
                    $emprunt = array_shift($empruntsParam);
                    $livre->addEmprunt($emprunt);
                }


                $manager->persist($livre);
                $livres[] = $livre;

            
        }
    }
}


