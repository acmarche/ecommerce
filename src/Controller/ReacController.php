<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReacController extends Controller
{
    /**
     * @Route("/reac", name="reac")
     */
    public function index()
    {
        return $this->render('reac/index.html.twig', [
            'controller_name' => 'ReacController',
        ]);
    }
}
