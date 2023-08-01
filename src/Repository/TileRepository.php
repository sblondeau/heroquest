<?php

namespace App\Repository;

use App\Entity\Tile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\ErrorHandler\Collecting;

/**
 * @method Tile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tile[]    findAll()
 * @method Tile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tile::class);
    }

    public function findMaskingTiles()
    {
        return $this->createQueryBuilder('t')
            ->orWhere('t.room is not null')
            // ->orWhere('t.occupant is not null')
            ->getQuery()
            ->getResult()
        ;
    }
}
