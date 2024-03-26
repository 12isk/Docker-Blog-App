<?php

namespace App\Command;

use App\Entity\Article;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:createArticles ',
    description: 'Creates all the articles',
)]
class CreateArticleCommand extends Command
{

    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('nb_articles', InputArgument::OPTIONAL, "Nombre d'articles")
            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nb_article = $input->getArgument('nb_articles');

        $io->warning('Creation de '.$nb_article.' articles.');

        if ($nb_article < 1){
            return Command::FAILURE;
        }

        for($counter =0; $counter < $nb_article; $counter++)
        {
            $article = new Article();
            $article->setTitle('Article numero'. $counter .'');
            $article->setContent('Ceci est le texte de l\'Article'. $counter .'');
            $article->setAuthor('Main man Nico');
            $article->setPubDate(new \DateTime());
            
            $this->em->persist($article);
        
            $io->warning('Creation de l\'article'.$counter);
        }
        $this->em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
