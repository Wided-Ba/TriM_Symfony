<?php

namespace App\Repository;

use App\Entity\pharmacien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<pharmacien>
 *
 * @method pharmacien|null find($id, $lockMode = null, $lockVersion = null)
 * @method pharmacien|null findOneBy(array $criteria, array $orderBy = null)
 * @method pharmacien[]    findAll()
 * @method pharmacien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PharmacienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, pharmacien::class);
    }

//    /**
//     * @return pharmacien[] Returns an array of pharmacien objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?pharmacien
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
