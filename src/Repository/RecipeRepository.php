<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findByQueryItems(array $items, $meal)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.items', 'i')
            ->innerJoin('r.meal', 'm')
            ->where('i.name IN (:items)')
            ->andWhere('m.id = :meal')
            ->setParameters([
                'items' => $items,
                'meal' => $meal->getId()
            ])
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
