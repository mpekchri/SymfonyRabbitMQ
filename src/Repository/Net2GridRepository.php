<?php

namespace App\Repository;

use App\Entity\Net2Grid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Net2Grid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Net2Grid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Net2Grid[]    findAll()
 * @method Net2Grid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Net2GridRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Net2Grid::class);
    }

    // /**
    //  * @return Net2Grid[] Returns an array of Net2Grid objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Net2Grid
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
