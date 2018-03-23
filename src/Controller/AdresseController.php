<?php

namespace App\Controller;

use App\Entity\Client\Adresse;
use App\Form\Client\AdresseType;
use App\Manager\AdresseManager;
use App\Repository\Client\AdresseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur/adresse")
 * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
 */
class AdresseController extends Controller
{
    /**
     * @var AdresseManager
     */
    private $adresseManager;

    public function __construct(AdresseManager $adresseManager)
    {
        $this->adresseManager = $adresseManager;
    }

    /**
     * @Route("/", name="acecommerce_adresse_index", methods="GET")
     */
    public function index(AdresseRepository $adresseRepository): Response
    {
        $user = $this->getUser();
        $adresses = $adresseRepository->findBy(['user' => $user->getUsername()]);

        $formDelete = $this->createDeleteForm();

        return $this->render(
            'adresse/index.html.twig',
            [
                'adresses' => $adresses,
                'user' => $user,
                'form_delete' => $formDelete->createView(),
            ]
        );
    }

    /**
     * @Route("/new", name="acecommerce_adresse_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $adresse = $this->adresseManager->newInstance($user);

        $form = $this->createForm(AdresseType::class, $adresse)
            ->add('submit', SubmitType::class, ['label' => 'Ajouter']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->adresseManager->insert($adresse);

            $this->addFlash('success', 'L\'adresse a bien été ajoutée');

            return $this->redirectToRoute('acecommerce_adresse_index');
        }

        return $this->render(
            'adresse/new.html.twig',
            [
                'adresse' => $adresse,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="acecommerce_adresse_edit", methods="GET|POST")
     * @Security("is_granted('show', adresse)")
     */
    public function edit(Request $request, Adresse $adresse): Response
    {
        $form = $this->createForm(AdresseType::class, $adresse)
            ->add('submit', SubmitType::class, ['label' => 'Update']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->adresseManager->flush();

            $this->addFlash('success', 'L\'adresse a bien été modifiée');

            return $this->redirectToRoute('acecommerce_adresse_index');
        }

        return $this->render(
            'adresse/edit.html.twig',
            [
                'adresse' => $adresse,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a adresse entity.
     *
     * @Route("/delete", name="acecommerce_adresse_delete")
     * @Method("DELETE")
     *
     */
    public function delete(Request $request, AdresseRepository $adresseRepository)
    {
        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adresseId = $request->request->get('idadresse', 0);

            if (!$adresse = $adresseRepository->find($adresseId)) {
                $this->addFlash('danger', 'Adresse introuvable');
                return $this->redirectToRoute('acecommerce_adresse_index');
            }

            $this->denyAccessUnlessGranted('delete', $adresse, "Vous n'avez pas accès à  cette adresse.");

            $this->adresseManager->delete($adresse);

            $this->addFlash('success', 'L\'adresse a bien été supprimée');
        }

        return $this->redirectToRoute('acecommerce_adresse_index');
    }

    /**
     * Creates a form to delete a adresse entity.
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_adresse_delete'))
            ->setMethod('DELETE')
            ->getForm();
    }
}
