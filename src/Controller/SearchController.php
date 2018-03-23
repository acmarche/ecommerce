<?php

namespace App\Controller;

use App\Entity\Commerce\Commerce;
use App\Entity\Lunch\Ingredient;
use App\Entity\Produit\Produit;
use App\Form\Search\RechercheProduit;
use App\Form\Search\SearchInlineType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SearchController
 * @package App\Controller
 * @Route("/search")
 *
 */
class SearchController extends AbstractController
{
    /**
     * Finds and displays
     *
     * @Route("/", name="acecommerce_search")
     * @Method({"GET"})
     */
    public function index(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $ingredients = $produits = $commerces = [];
        $key = "ecommerce_search";

        if ($session->has($key)) {
            $data = unserialize($session->get($key));
        }

        $motclef = $request->get('motclef');

        if ($motclef) {
            $data['motclef'] = $motclef;
        }

        $form = $this->createForm(
            RechercheProduit::class,
            $data
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('raz')->isClicked()) {
                $session->remove($key);
                $this->addFlash('info', 'La recherche a bien été réinitialisée.');

                return $this->redirectToRoute('acecommerce_search');
            }

            $dataForm = $form->getData();
            $data['motclef'] = $dataForm['motclef'];
            $data['commerce'] = $dataForm['commerce'];
        }

        if (count($data) > 0) {
            $session->set($key, serialize($data));
            $produits = $em->getRepository(Produit::class)->search($data);
            $commerces = $em->getRepository(Commerce::class)->search($data);
        }

        return $this->render(
            'produit/index.html.twig',
            [
                'form' => $form->createView(),
                'produits' => $produits,
                'commerces' => $commerces,
            ]
        );
    }
}
