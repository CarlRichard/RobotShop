<?php 

namespace App\Repository;

use App\Entity\DiscountCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DiscountCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountCode::class);
    }

    // Méthode personnalisée (optionnelle) pour des recherches avancées
    public function findValidDiscountCode(string $code): ?DiscountCode
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.code = :code')
            ->andWhere('d.isActive = true') // Si vous avez un champ `isActive`
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
