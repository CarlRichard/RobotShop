<?php

namespace App\Repository;

use App\Entity\Robot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Robot>
 */
class RobotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Robot::class);
    }

    public function paginate(Request $request): Paginator
    {
        $page = $request->query->getInt('page', 1);
        $queryBuilder = $this->createQueryBuilder('r')
                             ->setFirstResult(($page - 1) * 9) // Page actuelle * 9 robots par page
                             ->setMaxResults(9) // 9 robots par page
                             ->getQuery();
    
        return new Paginator($queryBuilder);
    }


    public function findByCategory($categoryId)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.category', 'c')
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Robot[] Returns an array of Robot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Robot
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
