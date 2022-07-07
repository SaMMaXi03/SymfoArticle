<?php


namespace App\Controller;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use ContainerSqmjNoM\getCategoryRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/insert-category", name="insert_category")
     */
    public function insertCategory(EntityManagerInterface $entityManager)
    {
        $category = new Category();

        $category->setTitle("Ecologie");
        $category->setColor("green");

        $entityManager->persist($category);
        $entityManager->flush();

        return new Response('OK');
    }

        /**
         * @Route("categories", name="categories")
         */
        public function listCategories(CategoryRepository $categoryRepository)
        {
           $categories = $categoryRepository->findAll();

           return $this->render('list_categories.html.twig', [
               'categories' => $categories
        ]);
    }

    /**
     * @Route("category/{id}",name="show_category")
     */
    public function showCategory($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        return $this->render('show_category.html.twig',[
            'category' => $category
        ]);
    }




}