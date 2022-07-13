<?php


namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{

    /**
     * @Route("/admin/article/{id}", name="admin_article")
     */
    public function showArticle(ArticleRepository $articleRepository, $id)
    {

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
        // je créé une instance de la classe d'entité Article
        // dans le but de créer un article en BDD
        $article = new Article();
        $article->setPublishedAt(new \DateTime('NOW'));

        // j'ai utilisé en ligne de commandes "php bin/console make:form"
        // pour que Symfony me créé une classe qui contiendra "le plan", "le patron"
        // du formulaire pour créer les articles. C'est la classe ArticleType
        // j'utilise la méthode $this->createForm pour créer un formulaire
        // en utilisant le plan du formulaire (ArticleType) et une instance d'Article
        $form = $this->createForm(ArticleType::class, $article);

        // On "donne" à la variable qui contient le formulaire
        // une instance de la classe  Request
        // pour que le formulaire puisse récupérer toutes les données
        // des inputs et faire les setters sur $article automatiquement
        $form->handleRequest($request);

        // si le formulaire a été posté et que les données sont valides (valeurs
        // des inputs correspondent à ce qui est attendu en bdd pour la table article)
        if ($form->isSubmitted() && $form->isValid()) {
            // alors on enregistre l'article en BDD
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article enregistré !');
        }

        // j'affiche mon twig, en lui passant une variable
        // form, qui contient la vue du formulaire, c'est à dire,
        // le résultat de la méthode createView de la variable $form
        return $this->render("admin/insert_article.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_delete_article")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        if (!is_null($article)) {
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien supprimé l\'article !');
        } else {
            $this->addFlash('error', 'Article introuvable ! ');
        }

        return $this->redirectToRoute('admin_articles');
    }


    /**
     * @Route("/admin/articles/update/{id}", name="admin_update_article")
     */
    public function updateArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $article = $articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        // On "donne" à la variable qui contient le formulaire
        // une instance de la classe  Request
        // pour que le formulaire puisse récupérer toutes les données
        // des inputs et faire les setters sur $article automatiquement
        $form->handleRequest($request);

        // si le formulaire a été posté et que les données sont valides (valeurs
        // des inputs correspondent à ce qui est attendu en bdd pour la table article)
        if ($form->isSubmitted() && $form->isValid()) {
            // alors on enregistre l'article en BDD
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article enregistré !');
        }

        // j'affiche mon twig, en lui passant une variable
        // form, qui contient la vue du formulaire, c'est à dire,
        // le résultat de la méthode createView de la variable $form
        return $this->render("admin/update_article.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/search", name="admin_search_articles")
     */
    public function searchArticles(Request $request, ArticleRepository $articleRepository)
    {
        // je récupère les valeurs du formulaire dans ma route
        $search = $request->query->get('search');

        // je vais créer une méthode dans l'ArticleRepository
        // qui trouve un article en fonction d'un mot dans son titre ou son contenu
        $articles = $articleRepository->searchByWord($search);

        // je renvoie un fichier twig en lui passant les articles trouvé
        // et je les affiche

        return $this->render('admin/search_articles.html.twig', [
            'articles' => $articles
        ]);
    }


}