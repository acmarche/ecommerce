<?php

namespace App\Controller\Admin;

use App\Entity\Attribut\ProduitListingAttribut;
use App\Form\Attribut\ProduitListingAttributType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ListingController
 * @package App\Controller
 * @Route("/admin/produitlisting")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class ProduitListingController extends Controller
{
    private $listingAttributManager;
    private $attributManager;
    private $listingAttributsRepository;

    /**
     * Finds and displays a  entity.
     *
     * @Route("/edit/{id}", name="acecommerce_admin_produitlisting_edit")
     * @Method({"GET","POST"})
     * @Security("is_granted('edit', produitListingAttribut)")
     */
    public function edit(Request $request, ProduitListingAttribut $produitListingAttribut)
    {
        $form = $this->createForm(ProduitListingAttributType::class, $produitListingAttribut);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $produit = $produitListingAttribut->getProduit();

            $this->addFlash('success', 'Le listing a bien été modifié');

            return $this->redirectToRoute('acecommerce_admin_produit_show', ['id' => $produit->getId()]);
        }

        return $this->render(
            'admin/produitlisting/edit.html.twig',
            [
                'produitListing' => $produitListingAttribut,
                'form' => $form->createView(),
            ]
        );
    }


}
