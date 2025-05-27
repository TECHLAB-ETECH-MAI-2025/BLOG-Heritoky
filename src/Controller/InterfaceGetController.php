<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\RecupererDonnerExterne;


final class InterfaceGetController extends AbstractController
{
#[Route('/taux_echanger/taux-echange', name: 'taux_echabger')]
public function recuperer(RecupererDonnerExterne $recupererDonnerExterne ){

$recuper = $recupererDonnerExterne->getCurrencyPage();

 return $this->render('taux_echange/taux_echange.html.twig', [
            'Donnerecuperer' => $recuper,
        ]);

}
}
