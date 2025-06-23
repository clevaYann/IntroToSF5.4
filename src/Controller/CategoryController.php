<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index', methods: ['GET'])]
    public function index(): Response
    {
        //récupérer tous les articles de la table article de la BD et les mettre dans le tableau $articles
        $categories= $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', ['category' => $categories]);
    }

    #[Route('/category/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRepository $categoryRepository): RedirectResponse|Response
    {
        $category = new Category();
        $form = $this->createFormBuilder($category)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/category/edit/{id}', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): RedirectResponse|Response
    {
        $form = $this->createFormBuilder($category)
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/delete/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('category_index');
    }
}
