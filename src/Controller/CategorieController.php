<?php

namespace App\Controller;

use App\Entity\Categorie\Categorie;
use App\Repository\Categorie\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategorieController
 * @package App\Controller
 * @Route("/categories")
 */
class CategorieController extends AbstractController
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="acecommerce_categorie_index")
     * @Method("GET")
     * @Template()
     */
    public function index(CategorieRepository $categorieRepository)
    {
        $categories = $categorieRepository->getRoots();

        return [
            'categories' => $categories,
        ];
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/{id}", name="acecommerce_categorie_show")
     * @Method("GET")
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render(
            'categorie/show.html.twig',
            ['categorie' => $categorie]
        );
    }
}
