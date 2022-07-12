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
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted () && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success','Article enregistré !');
        }

        return $this->render('admin/insert_article.html.twig', [
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
        $article=$articleRepository->find($id);

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash("success", " Article modifié ! ");
        }

        return $this->render("admin/update_article.html.twig", ["form"=>$form->createView()]);
    }




}