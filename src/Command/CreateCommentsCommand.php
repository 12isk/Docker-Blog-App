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

#[AsCommand(
    name: 'app:createComments',
    description: 'adds comments to an article',
)]
class CreateCommentsCommand extends Command
{

    private ArticleRepository $articleRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $em,
                                ArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->entityManager = $em;
        $this->articleRepository = $articleRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nb_comments', InputArgument::OPTIONAL, 'nb_argument','')
            ->addArgument('article_id', InputArgument::OPTIONAL, 'article id')
            
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nb_comments = $input->getArgument('nb_comments');
        $article_id = $input->getArgument('article_id');
        $io->warning('Creation de '.$nb_comments.' comments.');

        $article = $this->articleRepository->find($article_id);

        if ($nb_comments < 1){
            return Command::FAILURE;
        }

        for($counter =0; $counter < $nb_comments; $counter++)
        {
            $comment = new Comment();
            $comment->setText('Commentaire numero'. $counter .'');
            $comment->setAuthor('Lo and behold Nico');
            $comment->setPubDate(new \DateTime());
            $comment->setArticle($article);
            
            $this->entityManager->persist($comment);
        
            $io->warning('Creation du comment'.$counter);
        }
        $this->entityManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
    }

