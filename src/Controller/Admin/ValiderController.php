<?php

namespace App\Controller\Admin;

use App\Entity\Commande\Commande;
use App\Event\CommandeEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * ValiderController
 *
 * @Route("/admin/validation")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class ValiderController extends AbstractController
{
    /**
     * Liste des commandes à valider
     *
     * @Route("/", name="acecommerce_admin_commande_valider_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function index()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getCommandeAValider($user);

        return [
            'commandes' => $commandes,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="acecommerce_admin_commande_valider_edit")
     * @Method({"GET","POST"})
     * @Template()
     * @Security("is_granted('validate', commande)")
     */
    public function edit(Request $request, Commande $commande, EventDispatcherInterface $eventDispatcher)
    {
        if ($commande->getValide()) {
            throw $this->createAccessDeniedException('La commande a déjà été validée');
        }

        $form = $this->createForm(ValiderCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            // When triggering an event, you can optionally pass some information.
            // For simple applications, use the GenericEvent object provided by Symfony
            // to pass some PHP variables. For more complex applications, define your
            // own event object classes.
            // See https://symfony.com/doc/current/components/event_dispatcher/generic_event.html
            $event = new GenericEvent($commande);

            $event = new CommandeEvent($commande);
            $eventDispatcher->dispatch(CommandeEvent::COMMANDE_VALIDE, $event);

            $this->addFlash('success', 'La commande a bien été validée');

            return $this->redirectToRoute('acecommerce_admin_commande_show', ['id' => $commande->getId()]);
        }

        return [
            'commande' => $commande,
            'form' => $form->createView(),
        ];
    }

}
