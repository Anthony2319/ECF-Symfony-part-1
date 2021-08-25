<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository;

/**
 * @Route("/")
 */
class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }
}
