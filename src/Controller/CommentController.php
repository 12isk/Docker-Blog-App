<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;


class CommentController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager, private ArticleRepository $articleRepository) {
        }

    #[Route('/comment/', name: 'app_comment')]
    public function index( Request $request): Response
    {
        // just set up a fresh $task object (remove the example data) 
        
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('article')->getData();
            $article = $this->articleRepository->find($title);
            $comment->setArticle($article);
            $comment->setAuthor($form->get('author')->getData());
            $comment->setText($form->get('text')->getData());
            $comment->setPubDate($pubDate = new \DateTime());

            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            return $this->redirectToRoute(
                'app_article',
                ['id'=> $article->getId()]
            );

        }


        return $this->render('public/comment.html.twig', [
            'form'=> $form->createView(),
        ]);
    }
}
