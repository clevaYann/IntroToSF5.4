<?php

namespace App\Controller;

use App\Entity\Article;
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
    #[Route('/article', name: 'article_index', methods: ['GET'])]
    public function index(): Response
    {
        //récupérer tous les articles de la table article de la BD et les mettre dans le tableau $articles
        $articles= $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', ['articles' => $articles]);
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