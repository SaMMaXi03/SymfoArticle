<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/article/{id}", name="article")
     */
    public function showArticle(ArticleRepository $articleRepository, $id)
    {
        // récupérer depuis la base de données un article
        // en fonction d'un ID
        // donc SELECT * FROM article where id = xxx

        // la classe Repository me permet de faire des requête SELECT
        // dans la table associée
        // la méthode permet de récupérer un élément par rapport à son id
        $article = $articleRepository->find($id);

        return $this->render('show_article.html.twig', [
            'article' => $article
        ]);

    }

    /**
     * @Route("/articles", name="articles")
     */
    public function listArticles(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('list_articles.html.twig', [
            'articles' => $articles
        ]);
    }



    /**
     * @Route("insert-article", name="insert_article")
     */
    public function insertArticle(EntityManagerInterface $entityManager)
    {
        // je créé une instance de la classe Article (classe d'entité)
        // dans le but de créer un nouvel article dans ma bdd (table article)

        $article = new Article();

        // j'utilise les setters du titre, du contenu etc
        // pour mettre les données voulues pour le titre, le contenu etc
        $article->setTitle("Chat mignon");
        $article->setContent("ouuuh qu'il est troumignoninou ce petit chat. Et si je lui roulais dessus avec mon SUV");
        $article->setPublishedAt(new \DateTime('NOW'));
        $article->setIsPublished(true);

        // j'utilise la classe EntityManagerInterface de Doctrine pour
        // enregistrer mon entité dans la bdd dans la table article (en
        // deux étapes avec le persist puis le flush)
        $entityManager->persist($article);
        $entityManager->flush();

        dump($article); die;
    }

}