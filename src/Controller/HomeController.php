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

        return $this->render('home.html.twig',[
            'dernierarticle' => $dernierArticle
        ]);
        
    }
}


?>