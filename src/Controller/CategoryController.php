<?php


namespace App\Controller;


use App\Entity\Category;
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





}