<?php

namespace App\Controller\Admin;

use App\Entity\Livraison\LieuLivraison;
use App\Form\Livraison\LieuLivraisonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Lieu livraison controller.
 *
 * @Route("/admin/lieu_livraison")
 * @Security("has_role('ROLE_ECOMMERCE_ADMIN')")
 */
class LieuLivraisonController extends AbstractController
{
    /**
     * Lists all lieu_livraison entities.
     *
     * @Route("/", name="acecommerce_admin_lieulivraison_index")
     * @Method("GET")
     * @Template()
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $lieulivraisons = $em->getRepository(LieuLivraison::class)->findAll();

        return [
            'lieu_livraisons' => $lieulivraisons,
        ];
    }

    /**
     * Creates a new lieu_livraison entity.
     *
     * @Route("/new", name="acecommerce_admin_lieulivraison_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function new(Request $request)
    {
        $lieulivraison = new LieuLivraison();
        $form = $this->createForm(LieuLivraisonType::class, $lieulivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lieulivraison);
            $em->flush();

            $this->addFlash('success', 'Le lieux de livraison a bien été ajouté');

            return $this->redirectToRoute('acecommerce_admin_lieulivraison_index');
        }

        return [
            'lieu_livraison' => $lieulivraison,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a lieu_livraison entity.
     *
     * @Route("/{id}", name="acecommerce_admin_lieulivraison_show")
     * @Method("GET")
     * @Template()
     */
    public function show(LieuLivraison $lieulivraison)
    {
        $deleteForm = $this->createDeleteForm($lieulivraison);

        return [
            'lieu_livraison' => $lieulivraison,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing lieu_livraison entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_lieulivraison_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function edit(Request $request, LieuLivraison $lieulivraison)
    {
        $editForm = $this->createForm(LieuLivraisonType::class, $lieulivraison);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le lieux de livraison bien été modifié');

            return $this->redirectToRoute('acecommerce_admin_lieulivraison_show', ['id' => $lieulivraison->getId()]);
        }

        return [
            'lieu_livraison' => $lieulivraison,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a lieu_livraison entity.
     *
     * @Route("/{id}", name="acecommerce_admin_lieulivraison_delete")
     * @Method("DELETE")
     *
     */
    public function delete(Request $request, LieuLivraison $lieulivraison)
    {
        $form = $this->createDeleteForm($lieulivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lieulivraison);
            $em->flush();

            $this->addFlash('success', 'Le lieux de livraison a bien été supprimé');
        }

        return $this->redirectToRoute('acecommerce_admin_lieulivraison_index');
    }

    /**
     * Creates a form to delete a lieu_livraison entity.
     *
     * @param LieuLivraison $lieulivraison The lieu_livraison entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(LieuLivraison $lieulivraison)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_lieulivraison_delete', array('id' => $lieulivraison->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}
