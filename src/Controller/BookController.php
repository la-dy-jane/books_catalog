<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/book", name="book_")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/add", name="add")
     */
    public function addBook(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        $author = $this->getDoctrine()->getRepository(Author::class)->findOneBy(['id' => $request->query->get('author_id')]);
        $book->setAuthor($author);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getDoctrine()->getRepository(Book::class)->findBook($book->getTitle(),$book->getYear(),$author) ){
                $this->addFlash('danger', 'Книга с названием '.$book->getTitle().' у автора '.$author->getSurname().' '.$author->getSurname().' уже существует');
            }
            else {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($book);
                $entityManager->flush();
                
                return $this->redirectToRoute('author_books_list', ['id' => $request->query->get('author_id')]);
            }
        }
        return $this->render('book/add.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

}
