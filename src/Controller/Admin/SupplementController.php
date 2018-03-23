<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\Commerce;
use App\Entity\Lunch\Supplement;
use App\Form\Lunch\SupplementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * supplement controller.
 *
 * @Route("/admin/supplement")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class SupplementController extends AbstractController
{
    /**
     * Lists all supplement entities.
     *
     * @Route("/", name="acecommerce_admin_supplement_index")
     * @Method("GET")
     * @Template()
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            $supplements = $em->getRepository(Supplement::class)->findAll();
        } else {
            $supplements = $em->getRepository(Supplement::class)->getOwnedByUser($user);
        }

        return [
            'supplements' => $supplements,
        ];
    }

    /**
     * Creates a new supplement entity.
     *
     * @Route("/new/{commerce}", name="acecommerce_admin_supplement_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('addsupplement', commerce)")
     * @Template()
     */
    public function new(Request $request, Commerce $commerce)
    {
        $supplement = new Supplement();
        $supplement->setCommerce($commerce);

        $form = $this->createForm(SupplementType::class, $supplement)
            ->add('save', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplement);
            $em->flush();

            $this->addFlash('success', "Le supplément a bien été ajouté");

            $commerce = $supplement->getCommerce();

            return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'supplement' => $supplement,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a supplement entity.
     *
     * @Route("/{id}", name="acecommerce_admin_supplement_show")
     * @Method("GET")
     * @Security("is_granted('show', supplement)")
     * @Template()
     */
    public function show(Supplement $supplement)
    {
        $deleteForm = $this->createDeleteForm($supplement);

        return [
            'supplement' => $supplement,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing supplement entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_supplement_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', supplement)")
     * @Template()
     */
    public function edit(Request $request, Supplement $supplement)
    {
        $editForm = $this->createForm(SupplementType::class, $supplement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le supplément a bien été modifié");

            return $this->redirectToRoute('acecommerce_admin_supplement_show', array('id' => $supplement->getId()));
        }

        return [
            'supplement' => $supplement,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a supplement entity.
     *
     * @Route("/{id}", name="acecommerce_admin_supplement_delete")
     * @Security("is_granted('delete', supplement)")
     * @Method("DELETE")
     *
     */
    public function delete(Request $request, Supplement $supplement)
    {
        $form = $this->createDeleteForm($supplement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $commerce = $supplement->getCommerce();
            $em->remove($supplement);
            $em->flush();

            return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return $this->redirectToRoute('acecommerce_admin_commerce_index');
    }

    /**
     * Creates a form to delete a supplement entity.
     *
     * @param Supplement $supplement The supplement entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Supplement $supplement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_supplement_delete', array('id' => $supplement->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}
