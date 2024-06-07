<?php

namespace App\Repository;

use App\Entity\chefLab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<chefLab>
 *
 * @method chefLab|null find($id, $lockMode = null, $lockVersion = null)
 * @method chefLab|null findOneBy(array $criteria, array $orderBy = null)
 * @method chefLab[]    findAll()
 * @method chefLab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChefLabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, chefLab::class);
    }

//    /**
//     * @return chefLab[] Returns an array of chefLab objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?chefLab
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
