<?php

namespace App\Controller;

use App\Entity\Author;
use App\Service\AuthorHelper;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/author", name="author_")
 */

class AuthorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {        
        return $this->render('author/index.html.twig', [
            'authors' => $this->getDoctrine()->getRepository(Author::class)->findAll(),
        ]);
    }
    
    /**
     * @Route("/add", name="add")
     */
    public function addAuthor(Request $request, AuthorHelper $authorHelper): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
            if ($authorHelper->saveAuthor($author))
                return $this->redirectToRoute('author_index');
        
        return $this->render('author/add.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/edit/{id}", name="edit", methods={"GET","POST"})
     */
    public function editAuthor(Request $request, $id, AuthorHelper $authorHelper): Response
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        
        if (!$author) {
            throw $this->createNotFoundException(
                'Не найден автор с id '.$id
            );
        }
        
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
            if ($authorHelper->saveAuthor($author))
                return $this->redirectToRoute('author_index');
        
        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/books/{id}", name="books_list")
     */
    public function booksList($id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        
        if (!$author) {
            throw $this->createNotFoundException(
                'Не найден автор с id '.$id
            );
        }
        
        return $this->render('author/books_list.html.twig', ['author' => $author]);
    }    
    
}
