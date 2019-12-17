<?php

namespace App\Service; 

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Container\ContainerInterface;

class AuthorHelper
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    public function __construct(EntityManagerInterface $em,ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }
    
    public function saveAuthor($author):bool
    {
        $flashBag = $this->container->get('session')->getFlashBag();
        if ($this->em->getRepository(Author::class)->findByName($author->getName(),$author->getSurname()) ){
            $flashBag->add('danger', 'Автор с именем '.$author->getName().' и фамилией '.$author->getSurname().' уже существует');
            return false;
        }
        else {
            $flashBag->add('success', 'Автор с именем '.$author->getName().' и фамилией '.$author->getSurname().' успешно сохранен');
            $entityManager = $this->em;
            $entityManager->persist($author);
            $entityManager->flush();
            return true;
        }
    }
    
}
