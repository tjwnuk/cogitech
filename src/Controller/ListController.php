<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\VarDumper\VarDumper;

class ListController extends AbstractController
{
    public function fetchData(string $url)
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

    #[Route(path: '/list', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $data = $this->fetchData('https://jsonplaceholder.typicode.com/posts');
        // $data = VarDumper::dump($data);
        
        return $this->render('list.html.twig', [
            'data' => $data,
        ]);
    }
}
