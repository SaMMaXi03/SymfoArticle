<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     * @Route("/admin/insert-category", name="admin_insert_category")
     */
    public function insertCategories(EntityManagerInterface $entityManager, Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        return $this->render('admin/insert_category.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/categories", name="admin_list_categories")
     */
    public function listCategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/list_categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("admin/categories/{id}", name="admin_show_category")
     */
    public function showCategory($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render('admin/show_category.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/admin/categories/delete/{id}", name="admin_delete_category")
     */
    public function deleteArticle($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);

        if (!is_null($category)) {
            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien supprimé la category !');
        } else {
            $this->addFlash('error', 'Category introuvable ! ');
        }
        return $this->redirectToRoute('admin_list_categories');
    }



}