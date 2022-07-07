<?php

namespace App\Controller;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(ArticleRepository $articleRepository)
    {
        $lastArticles = $articleRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home.html.twig', [
            'lastArticles' => $lastArticles
        ]);
    }

}