<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PublicController extends AbstractController
{
    //TODO create a Home Route (will show articles)
    //TODO Load articles
    //TODO Pass articles to twig view
    //TODO Modify TWIGview to see the articles

    //TODO Create another Route 'Article' -> will show article + comments
    //TODO Charge a single article + comments 
    //TODO Pass info to TWIG view
    //TODO Modify TWIG view
    
    //TODO Link to Home TWIG view 


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
        ]);

    }

    #[Route('/article/{id}', name:'app_article')]
    public function showArticle(int $id): Response{

        $article = $this->articleRepository->find($id);
        
        return $this->render('public/article.html.twig', [
            'article' => $article,
            'comments' => $article->getComments()
        ]);
}
}
