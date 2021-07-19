<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }
    public function findByGenre($genre)
    {
        return $this->createQueryBuilder('l')
        ->innerJoin('l.genres', 'g')
        ->andWhere('g.nom LIKE :genre')
        ->setParameter('genre', "%{$genre}%")
        ->orderBy('l.id','ASC')
        // ->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }

    public function findByAuteurId($auteurId)
    {
        return $this->createQueryBuilder('l')
        ->innerJoin('l.auteur', 'a')
        ->andWhere('a.id LIKE :auteurId')
        ->setParameter('auteurId', "{$auteurId}")
        ->orderBy('l.id','ASC')
        // ->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }

    public function findByTitle($title)
    {
        return $this->createQueryBuilder('l')
        ->where('l.titre LIKE :title')
        ->setParameter('title', "%{$title}%")
        ->orderBy('l.id','ASC')
        // ->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }
    // /**
    //  * @return Livre[] Returns an array of Livre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Livre
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
