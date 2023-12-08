<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

use Symfony\Component\Security\Core\Security;

use App\Entity\Article;

class ListController extends AbstractController
{
    public function __construct(Security $security)
    {
        // $this->user = $security->getToken()->getUser();
        $this->security = $security;
    }
    
    #[Route(path: '/lista', name: 'list', methods: ['GET'])]
    public function list(PersistenceManagerRegistry $doctrine): Response
    {
        $data = [];
        $entityManager = $doctrine->getManager();
        $repo = $entityManager->getRepository(Article::class);
        $query  = $repo->createQueryBuilder('article')->orderBy('article.id')->getQuery();
        $security = $this->security;
        $user = null;

        $data = $query->getResult();
        if($security->getToken() != null) {
            $user = $security->getUser();
        }

        return $this->render('list.html.twig', [
            'data' => $data,
            'user' => $user->getUserIdentifier(),
        ]);
    }
}
