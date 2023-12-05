<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Article;

class fetchCommand extends Command
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManager = $entityManagerInterface;

        parent::__construct();
    }
    public function fetchArticles(string $url) : array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if($response->getStatusCode() === 200)
        {
            $data = $response->toArray();
            return $data;
        }

        return [];
    }

    public function fetchAuthors(string $url) : array
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200)
        {
            $authors = $response->toArray();
            return $authors;
        }

        return [];
    }

    public function mixArticlesWithAuthors(array $articles, array $authors) : array
    {
        $result = [];

        foreach ($articles as $key => $article) 
        {
            $userId = $article['userId'];
            $name = "";

            foreach($authors as $author)
            {
                if($userId == $author['id'])
                {
                    $name = $author['name'];
                }
            }

            $newElement = [
                'id' => $article['id'],
                'name' => $name,
                'title' => $article['title'],
                'body' => $article['body']
            ];

            array_push($result, $newElement);
        }

        return $result;
    }

    protected function configure()
    {
        $this->setName('fetch')
            ->setDescription('Fetch article list from https://jsonplaceholder.typicode.com/posts');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $articles = $this->fetchArticles('https://jsonplaceholder.typicode.com/posts');
        $authors = $this->fetchAuthors('https://jsonplaceholder.typicode.com/users');

        $data = $this->mixArticlesWithAuthors($articles, $authors);
        $entityManager = $this->entityManager;

        if(count($data) > 0)
        {
            foreach ($data as $article)
            {
                $newObj = new Article();
                
                $newObj->setName($article['name']);
                $newObj->setArticleId($article['id']);
                $newObj->setTitle($article['title']);
                $newObj->setBody($article['body']);

                $entityManager->persist($newObj);
                $entityManager->flush();
            }

            $size = count($data);
            $output->writeln("<info>Fetched successfully $size items.</info>");
            
            return Command::SUCCESS;
        }
        else
        {
            $output->writeln("<error>Could not fetch any data.</error>");
            return Command::FAILURE;
        }

    }
}