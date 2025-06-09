<?php

namespace App\Controller\Api;

use App\Entity\Article;


use App\Repository\ArticleRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Attribute\Route;

 use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api/article')]
final class ArticleControllerApi extends AbstractController
{
   

#[Route('/', name: 'api_article_index' , methods:['GET'])]
public function index(ArticleRepository $articleRepository, SerializerInterface $serializer): JsonResponse
    {
        $dernierArticles = $articleRepository->findLast();
        $articles = $articleRepository->findAll();

        $data = [
            'dernierArticles' => $dernierArticles,
            'articles' => $articles,
        ];

        $json = $serializer->serialize($data, 'json', ['groups' => ['article:read']]);

        return JsonResponse::fromJsonString($json, 200);
    }



}
