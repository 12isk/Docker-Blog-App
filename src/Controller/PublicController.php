<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    //TODO CREATE A COMMENT FORM
    

    private ArticleRepository $articleRepository;
    
    

    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }


    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $articles = $this->articleRepository->findAll();



        return $this->render('public/index.html.twig', [
            'articles' => $articles,
            'user'=> $this->getUser(),
        ]);

    }

    #[Route('/article/{id}', name:'app_article')]
    public function showArticle(int $id): Response{

        $article = $this->articleRepository->find($id);

        

        
        return $this->render('public/article.html.twig', [
            'article' => $article,
            'comments' => $article->getComments(),
            
        ]);
    }
}

