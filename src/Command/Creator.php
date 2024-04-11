<?php

namespace App\Command;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class Creator{

    private ArticleRepository $articleRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $em,
                                ArticleRepository $articleRepository)
    {
    
        $this->entityManager = $em;
        $this->articleRepository = $articleRepository;
    }

    public static function CreateSingleComment(Article $article, EntityManagerInterface $em, ArticleRepository $articleRepository, string $author, string $text): void{

        $comment = new Comment();
        $comment->setAuthor($author);
        $comment->setText($text);
        $comment->setPubDate(new \DateTime());

        $article = $articleRepository->find($article->getId());

        $comment->setArticle($article);

        $em->persist($comment);
        $em->flush();   
        
    }

}
