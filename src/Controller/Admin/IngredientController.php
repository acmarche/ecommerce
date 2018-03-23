<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\Commerce;
use App\Entity\Lunch\Ingredient;
use App\Form\Lunch\IngredientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * ingredient controller.
 *
 * @Route("/admin/ingredient")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class IngredientController extends AbstractController
{
    /**
     * Lists all ingredient entities.
     *
     * @Route("/", name="acecommerce_admin_ingredient_index")
     * @Method("GET")
     * @Template()
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            $ingredients = $em->getRepository(Ingredient::class)->findAll();
        } else {
            $ingredients = $em->getRepository(Ingredient::class)->getOwnedByUser($user);
        }

        return [
            'ingredients' => $ingredients,
        ];
    }

    /**
     * Creates a new ingredient entity.
     *
     * @Route("/new/{commerce}", name="acecommerce_admin_ingredient_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('addingredient', commerce)")
     * @Template()
     */
    public function new(Request $request, Commerce $commerce)
    {
        $ingredient = new Ingredient();
        $ingredient->setCommerce($commerce);

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('success', "L'ingrédient a bien été ajouté");

            $commerce = $ingredient->getCommerce();


            return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ingredient entity.
     *
     * @Route("/{id}", name="acecommerce_admin_ingredient_show")
     * @Method("GET")
     * @Security("is_granted('show', ingredient)")
     * @Template()
     */
    public function show(Ingredient $ingredient)
    {
        $deleteForm = $this->createDeleteForm($ingredient);

        return [
            'ingredient' => $ingredient,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing ingredient entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_ingredient_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', ingredient)")
     * @Template()
     */
    public function edit(Request $request, Ingredient $ingredient)
    {
        $editForm = $this->createForm(IngredientType::class, $ingredient);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'ingrédient a bien été modifié");

            return $this->redirectToRoute('acecommerce_admin_ingredient_show', array('id' => $ingredient->getId()));
        }

        return [
            'ingredient' => $ingredient,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ingredient entity.
     *
     * @Route("/{id}", name="acecommerce_admin_ingredient_delete")
     * @Security("is_granted('delete', ingredient)")
     * @Method("DELETE")
     *@Security("is_granted('delete', ingredient)")
     */
    public function delete(Request $request, Ingredient $ingredient)
    {
        $form = $this->createDeleteForm($ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $commerce = $ingredient->getCommerce();
            $em->remove($ingredient);
            $em->flush();

            return $this->redirectToRoute('acecommerce_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return $this->redirectToRoute('acecommerce_admin_commerce_index');
    }

    /**
     * Creates a form to delete a ingredient entity.
     *
     * @param Ingredient $ingredient The ingredient entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Ingredient $ingredient)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_ingredient_delete', array('id' => $ingredient->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}
