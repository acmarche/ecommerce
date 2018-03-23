<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\Commerce;
use App\Form\Commerce\CommerceType;
use App\Service\Bottin;
use App\Service\FilterQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * dommerce controller.
 *
 * @Route("/admin/commerce")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE') or has_role('ROLE_ECOMMERCE_LOGISTICIEN')")
 */
class CommerceController extends AbstractController
{
    /**
     * Lists all commerce entities.
     *
     * @Route("/", name="acecommerce_admin_commerce_index")
     * @Method("GET")
     * @Template()
     */
    public function index(FilterQuery $filterQuery)
    {
        $em = $this->getDoctrine()->getManager();

        $args = $filterQuery->getAllCommerces($this->getUser());
        $commerces = $em->getRepository(Commerce::class)->search($args);

        return [
            'commerces' => $commerces,
        ];
    }

    /**
     * Creates a new commerce entity.
     *
     * @Route("/new", name="acecommerce_admin_commerce_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ECOMMERCE_ADMIN')")
     * @Template()
     */
    public function new(Request $request)
    {
        $commerce = new Commerce();

        $form = $this->createForm(CommerceType::class, $commerce)
            ->add('Create', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commerce);
            $em->flush();

            $this->addFlash('success', 'Le commerce a bin été ajouté');

            return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'commerce' => $commerce,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a commerce entity.
     *
     * @Route("/{id}", name="acecommerce_admin_commerce_show")
     * @Method("GET")
     * @Security("is_granted('show', commerce)")
     * @Template()
     */
    public function show(Commerce $commerce, Bottin $bottin)
    {
        $deleteForm = $this->createDeleteForm($commerce);

        $error = $fiche = false;
        try {
            $fiche = $bottin->getFiche($commerce->getBottinId());
        } catch (\Exception $e) {
            $error = $e->getMessage();
            $this->addFlash('warning', $e->getMessage());
        }

        return [
            'error' => $error,
            'fiche' => $fiche,
            'commerce' => $commerce,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing commerce entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_commerce_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', commerce)")
     * @Template()
     */
    public function edit(Request $request, Commerce $commerce)
    {
        $editForm = $this->createForm(
            CommerceType::class,
            $commerce,
            [
                'requiredImage' => false,
            ]
        )
            ->add('Update', SubmitType::class);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le commerce a bin été mis à jour');

            return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'commerce' => $commerce,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a commerce entity.
     *
     * @Route("/{id}", name="acecommerce_admin_commerce_delete")
     * @Security("is_granted('delete', commerce)")
     * @Method("DELETE")
     */
    public function delete(Request $request, Commerce $commerce)
    {
        $form = $this->createDeleteForm($commerce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commerce);
            $em->flush();
        }

        return $this->redirectToRoute('acecommerce_admin_commerce_index');
    }

    /**
     * Creates a form to delete a commerce entity.
     *
     * @param Commerce $commerce The commerce entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Commerce $commerce)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_commerce_delete', array('id' => $commerce->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}
