<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{

    #[Route(path: '/lista', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $data = [];
        return $this->render('list.html.twig', [
            'data' => $data,
        ]);
    }
}
