<?php

namespace App\Controller\Admin;

use App\Entity\Attribut\ProduitListingAttribut;
use App\Entity\Commerce\Commerce;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Prix\Prix;
use App\Entity\Produit\Produit;
use App\Event\ProduitEvent;
use App\Form\Attribut\ProduitListingAttributType;
use App\Form\Produit\ProduitRapideType;
use App\Form\Produit\ProduitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * produit controller.
 *
 * @Route("/admin/produit")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class ProduitController extends AbstractController
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="acecommerce_admin_produit_index")
     * @Method("GET")
     * @Template()
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            $args = ['indisponible' => 2];
            $produits = $em->getRepository(Produit::class)->search($args);
        } else {
            $produits = $em->getRepository(Produit::class)->getOwnedByUser($user);
        }

        return [
            'produits' => $produits,
        ];
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new/{commerce}/{food}", name="acecommerce_admin_produit_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('addproduit', commerce)")
     * @Template()
     */
    public function new(Request $request, Commerce $commerce, $food = false)
    {
        $produit = new Produit();
        $prix = new Prix();
        $produit->setIsFood($food);
        $produit->setCommerce($commerce);
        $produit->setPrix($prix);

        $form = $this->createForm(ProduitRapideType::class, $produit);

        $form->add('Create', SubmitType::class)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($produit);
            $em->flush();

            $dispatcher = new EventDispatcher();
            $dispatcher->dispatch(ProduitEvent::PRODUIT_NEW, new ProduitEvent($produit));

            $this->addFlash('success', 'Le produit a bien été ajouté');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute(
                    'acecommerce_admin_produit_new',
                    ['commerce' => $commerce->getId(), 'food' => $food]
                );
            }

            return $this->redirectToRoute('acecommerce_admin_produit_show', array('id' => $produit->getId()));
        }

        return [
            'produit' => $produit,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="acecommerce_admin_produit_show")
     * @Method({"GET","POST"})
     * @Security("is_granted('show', produit)")
     * @Template()
     */
    public function show(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $images = $produit->getImages();

        $liaison = new ProduitListingAttribut();
        $liaison->setProduit($produit);

        $form_liaison = $this->createForm(ProduitListingAttributType::class, $liaison);
        $form_liaison_delete = $this->createDeleteLiaisonForm($produit);

        $form_liaison->handleRequest($request);

        if ($form_liaison->isSubmitted() && $form_liaison->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($liaison);
            $em->flush();

            $this->addFlash('success', 'Le listing a bien été lié');
        }

        return [
            'produit' => $produit,
            'images' => $images,
            'delete_form' => $deleteForm->createView(),
            'form_liaison' => $form_liaison->createView(),
            'form_liaison_remove' => $form_liaison_delete->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_produit_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', produit)")
     * @Template()
     */
    public function edit(Request $request, Produit $produit)
    {
        $editForm = $this->createForm(ProduitType::class, $produit)
            ->add('Update', SubmitType::class);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $dimension = $editForm->getData()->getDimension();
            //todo
            $dimension->setProduit($produit);

            $em->flush();
            $this->addFlash('success', 'Le produit a bien été mis à jour');

            return $this->redirectToRoute('acecommerce_admin_produit_show', array('id' => $produit->getId()));
        }

        return [
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}", name="acecommerce_admin_produit_delete")
     * @Method("DELETE")
     * @Security("is_granted('delete', produit)")
     */
    public function delete(Request $request, Produit $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);
        $commerce = $produit->getCommerce();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
    }

    /**
     *
     * @Route("/remove/listing/{id}", name="acecommerce_admin_listing_produit_remove")
     * @Method("DELETE")
     * @Security("is_granted('edit', produit)")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     */
    public function removeListing(Request $request, Produit $produit)
    {
        $em = $this->getDoctrine()->getManager();
        $produitListingAttributId = $request->request->get('produitListingAttribut');

        $produitListingAttribut = $em->getRepository(ProduitListingAttribut::class)->find($produitListingAttributId);

        if (!$produitListingAttribut) {
            $this->addFlash('danger', 'Listing introuvable');

            return $this->redirectToRoute('acecommerce_admin_produit_show', ['id' => $produit->getId()]);
        }

        $form = $this->createDeleteLiaisonForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->denyAccessUnlessGranted(
                'delete',
                $produitListingAttribut,
                "Vous n'avez pas accès a cette liste."
            );

            $em->remove($produitListingAttribut);
            $em->flush();

            $this->addFlash('success', 'Le listing a bien été détaché.');
        }

        return $this->redirectToRoute('acecommerce_admin_produit_show', ['id' => $produit->getId()]);
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteLiaisonForm(ProduitInterface $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_listing_produit_remove', ['id' => $produit->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
