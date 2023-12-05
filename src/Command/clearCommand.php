<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Article;

class clearCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('clear')
            ->setDescription('Clear database entries');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $entityManager = $this->entityManager;
        $entityManager->getConnection()->executeQuery('DROP TABLE article');
        
        $tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $tool->updateSchema($classes, true);

        $output->writeln("<info>Database cleared successfully</info>");

        
        return Command::SUCCESS;
    }
}