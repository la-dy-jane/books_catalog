<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
    
    public function findByName($name,$surname): ?Author
    {
        return $this->createQueryBuilder('a')
            ->where('a.name = :name')
            ->andWhere('a.surname = :surname')
            ->setParameter('name', $name)
            ->setParameter('surname', $surname)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
