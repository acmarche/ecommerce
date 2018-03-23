<?php

namespace App\Controller;

use App\Form\Security\UtilisateurPasswordType;
use App\Form\Security\UtilisateurType;
use App\Repository\Client\AdresseRepository;
use App\Repository\Commande\CommandeRepository;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UtilisateurController
 * @package App\Controller
 * @Route("/utilisateur")
 * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
 */
class UtilisateurController extends AbstractController
{
    /**
     * Finds and displays a utilisateur entity.
     *
     * @Route("/", name="acecommerce_utilisateur_show")
     * @Method("GET")
     *
     * @Template()
     */
    public function show(CommandeRepository $commandeRepository, AdresseRepository $adresseRepository)
    {
        $user = $this->getUser();
        $commandes = $commandeRepository->findBy(['user' => $user->getUsername()]);
        $adresses = $adresseRepository->findBy(['user' => $user->getUsername()]);

        return [
            'user' => $user,
            'commandes' => $commandes,
            'adresses' => $adresses,
        ];
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/edit", name="acecommerce_utilisateur_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $editForm = $this->createForm(UtilisateurType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Modification effectuée');

            return $this->redirectToRoute('acecommerce_utilisateur_show');
        }

        return [
            'user' => $user,
            'form' => $editForm->createView(),
        ];
    }


    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/password", name="acecommerce_utilisateur_password")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function password(Request $request, UserManager $userManager)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UtilisateurPasswordType::class, $user)
            ->add('submit', SubmitType::class, ['label' => 'Valider']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->setPassword($user, $form->getData()->getPlainPassword());
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Mot de passe changé');

            return $this->redirectToRoute('acecommerce_utilisateur_show');
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    public function info(AdresseRepository $adresseRepository)
    {
        $user = $this->getUser();
        $adresses = $adresseRepository->findBy(['user' => $user->getUsername()]);

        return $this->render(
            'utilisateur/info.html.twig',
            [
                'user' => $user,
                'adresse' => $adresses,
            ]
        );
    }
}
