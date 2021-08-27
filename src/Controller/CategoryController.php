<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $repo): Response
    {
        $cats = $repo->findAll();
        return $this->render('category/index.html.twig', [
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/category/{name}", name="oneCategory")
     */
    public function show(Category $cat): Response
    {
        return $this->render('category/one_category.html.twig', [
            'cat' => $cat,
        ]);
    }
}
