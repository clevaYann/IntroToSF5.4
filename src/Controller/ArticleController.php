<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article_index', methods: ['GET'])]
    public function index(): Response
    {
        //récupérer tous les articles de la table article de la BD et les mettre dans le tableau $articles
        $articles= $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', ['articles' => $articles]);
    }
    /**
     * @Route("/article/new")
     */
    public function new(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setName('Article 1');
        $article->setPrice(1000);

        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('Article enregistré avec id ' . $article->getId());
    }
}