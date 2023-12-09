<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

use App\Entity\Article;

class ListController extends AbstractController
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route(path: '/lista', name: 'list', methods: ['GET'])]
    public function list(PersistenceManagerRegistry $doctrine, Request $request): Response
    {
        $data = [];
        $entityManager = $doctrine->getManager();
        $repo = $entityManager->getRepository(Article::class);
        $query  = $repo->createQueryBuilder('article')->orderBy('article.id')->getQuery();
        $data = $query->getResult();

        $security = $this->security;
        $user = null;
        $username = null;
        if($security->getToken() != null) {
            $user = $security->getUser();
            $username = $user->getUserIdentifier();
            $loggedIn = true;
        }
        else {
            $loggedIn = false;
        }

        $message = null;
        $session = $request->getSession();
        $message = $session->getFlashBag()->get('info');
        

        return $this->render('list.html.twig', [
            'data' => $data,
            'username' => $username,
            'loggedIn' => $loggedIn,
            'message' => $message,
        ]);
    }

    #[Route(path: '/lista/delete/{id}', name: 'delete', methods: ['GET'])]
    public function delete($id, PersistenceManagerRegistry $doctrine, Request $request): Response
    {

        $entityManager = $doctrine->getManager();
        $repo = $entityManager->getRepository(Article::class);

        $entity = $repo->find($id);

        if(!$entity)
        {
            throw $this->createNotFoundException("Not found record of id $id");
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        $this->addFlash('info', "Item of id $id removed successfully.");

        return $this->redirectToRoute('list');
    }
}
