<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function list(): Response
    {
        $security = $this->security;
        $user = null;
        $username = null;
        if($security->getToken() != null) {
            $user = $security->getUser();
            $username = $user->getUserIdentifier();
        }
        
        return $this->render('home.html.twig', [
            'username' => $username,
        ]);
    }
}
