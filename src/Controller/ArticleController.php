<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleSearch;
use App\Form\ArticleSearchType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();


        $propertySearch = new ArticleSearch();
        $form = $this->createForm(ArticleSearchType::class, $propertySearch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom d'article tapé dans le formulaire
            $name = $propertySearch->getName();

            if ($name !== "")  //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
            {
                $articleSearch = $articleRepository->findBy(['name' => $name]);
                return $this->render('article/indexSearch.html.twig', ['articles' => $articleSearch]);
            }
        }

        return $this->render('article/index.html.twig', ['form' => $form->createView(), 'articles' => $articles]);
    }

    #[Route('/article/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository): RedirectResponse|Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        // 👇 Il manque cette ligne dans ton code
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article, true);

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', array('article' => $article));
    }
    #[Route('/article/edit/{id}', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): RedirectResponse|Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        // Manquant dans ton code : traitement de la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush(); // Pas besoin de persist() pour une entité existante

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/article/delete/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('article_index');
    }
}