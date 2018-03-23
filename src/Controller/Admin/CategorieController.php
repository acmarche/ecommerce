<?php

namespace App\Controller\Admin;

use App\Entity\Categorie\Categorie;
use App\Form\Categorie\CategorieType;
use App\Repository\Categorie\CategorieImageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * categorie controller.
 *
 * @Route("/admin/categorie")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class CategorieController extends AbstractController
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="acecommerce_admin_categorie_index")
     * @Method("GET")
     * @Template()
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(Categorie::class)->search([]);

        return [
            'categories' => $categories,
        ];
    }

    /**
     * Creates a new categorie entity.
     *
     * @Route("/new", name="acecommerce_admin_categorie_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function new(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bin été ajoutée');

            return $this->redirectToRoute('acecommerce_admin_categorie_index');
        }

        return [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/{id}", name="acecommerce_admin_categorie_show")
     * @Method("GET")
     * @Template()
     */
    public function show(Categorie $categorie, CategorieImageRepository $imageCategorieRepository)
    {
        $deleteForm = $this->createDeleteForm($categorie);

        $images = $imageCategorieRepository->findBy(['categorie'=>$categorie]);

        return [
            'categorie' => $categorie,
            'images' => $images,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_categorie_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function edit(Request $request, Categorie $categorie)
    {
        $editForm = $this->createForm(CategorieType::class, $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bin été modifiée');

            return $this->redirectToRoute('acecommerce_admin_categorie_show', ['id' => $categorie->getId()]);
        }

        return [
            'categorie' => $categorie,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a categorie entity.
     *
     * @Route("/{id}", name="acecommerce_admin_categorie_delete")
     * @Method("DELETE")
     *
     */
    public function delete(Request $request, Categorie $categorie)
    {
        $form = $this->createDeleteForm($categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bin été supprimée');
        }

        return $this->redirectToRoute('acecommerce_admin_categorie_index');
    }

    /**
     * Creates a form to delete a categorie entity.
     *
     * @param Categorie $categorie The categorie entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Categorie $categorie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_categorie_delete', array('id' => $categorie->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}
