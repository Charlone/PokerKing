<?php

namespace App\Repository;

use App\Entity\GameBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameBalance[]    findAll()
 * @method GameBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameBalance::class);
    }

    // /**
    //  * @return GameBalance[] Returns an array of GameBalance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameBalance
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
