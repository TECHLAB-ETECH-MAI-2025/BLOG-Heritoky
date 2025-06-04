<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController {
    
    #[Route('/', name:'home')]
    public function index(ArticleRepository $articleRepository): Response{

        $dernierArticle = $articleRepository->findLast();
        $articles = $articleRepository->findAll();

         if ($dernierArticle) {
            foreach ($dernierArticle as $article) {
                $article->commentsCount = $article->getComments()->count();
            }
            }

            foreach ($articles as $article) {
                $article->likeCount = $article->getLikes()->count();
                $article->commentsCount = $article->getComments()->count();
            }

        return $this->render('home.html.twig',[
            'dernierarticle' => $dernierArticle,
            'articles' => $articles
        ]);
        
    }
}


?>