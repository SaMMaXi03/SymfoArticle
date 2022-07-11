<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{

    /**
     * @Route("/admin/article/{id}", name="admin_article", requirements={"page"="\d+"})
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

        return $this->render('admin/show_article.html.twig', [
            'article' => $article
        ]);

    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function listArticles(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render('admin/list_articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/insert-article", name="admin_insert_article")
     */
    public function insertArticle(EntityManagerInterface $entityManager, Request $request)
    {
        $title = $request->query->get('title');
        $content = $request->query->get('content');

        if (!empty($title) &&
            !empty($content)
        ) {
            // je créé une instance de la classe Article (classe d'entité)
            // dans le but de créer un nouvel article dans ma bdd (table article)
            $article = new Article();

            // j'utilise les setters du titre, du contenu etc
            // pour mettre les données voulues pour le titre, le contenu etc
            $article->setTitle($title);
            $article->setContent($content);
            $article->setPublishedAt(new \DateTime('NOW'));
            $article->setIsPublished(true);

            // j'utilise la classe EntityManagerInterface de Doctrine pour
            // enregistrer mon entité dans la bdd dans la table article (en
            // deux étapes avec le persist puis le flush)
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien ajouté l\'article !');
            return $this->redirectToRoute("admin_articles");
        }

        $this->addFlash('error', 'Merci de remplir le titre et le contenu !');
        return $this->render('admin/insert_article.html.twig');
    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        // je récupère l'article en fonction de l'id dans l'url
        $article = $articleRepository->find($id);

        // je vérifie que la variable $article ne contient
        // pas null, donc que l'article existe en bdd
        if (!is_null($article)) {
            // j'utilise l'entity manager pour supprimer l'article
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien supprimé l\'article !');
        } else {
            $this->addFlash('error', 'Article introuvable ! ');

        }

        return $this->redirectToRoute('admin_articles');
    }


    //Mise à jour pour changer le titre

    /**
     * @Route("/admin/articles/update/{id}", name="admin_update_article")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
       $article = $articleRepository->find($id);

       $article->setTitle("title updated");

       $entityManager->persist($article);
       $entityManager->flush();

       return new Response('Update');

    }
}