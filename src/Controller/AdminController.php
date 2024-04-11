<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;


class AdminController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager, private ArticleRepository $articleRepository) {
        }


    #[Route('/add_art', name: 'app_create_art')]
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');


        $form = $this->createForm(ArticleType::class, $article = new Article());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($form->get('author')->getData());
            $article->setContent($form->get('content')->getData());
            $article->setPubDate(new \DateTime());
            $article->setTitle($form->get('title')->getData());

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home' );
        }



        return $this->render('admin/create_article.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/modify/{id}', name: 'app_mod_art')]
    public function modify(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // or add an optional message - seen by developers
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $article = $this->articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setAuthor($form->get('author')->getData());
            $article->setContent($form->get('content')->getData());
            $article->setTitle($form->get('title')->getData());

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_article', ['id'=> $article->getId()]);
        }

        return $this->render('admin/create_article.html.twig', [
            'form'=> $form->createView(),
            
        ]);
    }
}
