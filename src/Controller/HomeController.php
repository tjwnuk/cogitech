<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/home', name: 'home', methods: ['GET'])]
    public function list(): Response
    {
        return new Response('Home Controller');
    }
}
