<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\VarDumper\VarDumper;

class ListController extends AbstractController
{
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

    #[Route(path: '/list', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $articles = $this->fetchArticles('https://jsonplaceholder.typicode.com/posts');
        $authors = $this->fetchAuthors('https://jsonplaceholder.typicode.com/users');
        // $data = VarDumper::dump($data);

        $data = $this->mixArticlesWithAuthors($articles, $authors);
        
        return $this->render('list.html.twig', [
            'data' => $data,
        ]);
    }
}
