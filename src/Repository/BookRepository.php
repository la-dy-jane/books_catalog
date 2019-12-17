<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findBook($title, $year, $author): ?Book
    {
        return $this->createQueryBuilder('a')
        ->where('a.title = :title')
        ->andWhere('a.year = :year')
        ->andWhere('a.author = :author')
        ->setParameter('title', $title)
        ->setParameter('year', $year)
        ->setParameter('author', $author)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
