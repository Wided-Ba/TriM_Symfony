<?php

namespace App\Repository;

use App\Entity\infirmier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<infirmier>
 *
 * @method infirmier|null find($id, $lockMode = null, $lockVersion = null)
 * @method infirmier|null findOneBy(array $criteria, array $orderBy = null)
 * @method infirmier[]    findAll()
 * @method infirmier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfirmierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, infirmier::class);
    }

//    /**
//     * @return infirmier[] Returns an array of infirmier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?infirmier
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
