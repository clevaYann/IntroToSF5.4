<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_index', methods: ['GET'])]
    public function index(): Response
    {
        $articles = ['Article 1', 'Article 2','Article 3'];
        return $this->render('article/index.html.twig',['articles' => $articles]);
    }
}