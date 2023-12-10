<?php

namespace App\Repository;

use App\Entity\Columns;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Columns>
 *
 * @method Columns|null find($id, $lockMode = null, $lockVersion = null)
 * @method Columns|null findOneBy(array $criteria, array $orderBy = null)
 * @method Columns[]    findAll()
 * @method Columns[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Columns::class);
    }

//    /**
//     * @return Columns[] Returns an array of Columns objects
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

//    public function findOneBySomeField($value): ?Columns
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
