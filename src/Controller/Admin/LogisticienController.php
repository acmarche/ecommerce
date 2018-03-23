<?php

namespace App\Controller\Admin;

use App\Entity\Commande\Commande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Event\CommandeEvent;
use App\Form\Commande\TraiterCommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * logisticien controller.
 *
 * @Route("/admin/logisticien")
 * @Security("has_role('ROLE_ECOMMERCE_LOGISTICIEN')")
 */
class LogisticienController extends AbstractController
{
    /**
     * Liste des commandes a traiter
     *
     * @Route("/", name="acecommerce_logisticien_commande_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getCommandeALivrer();

        return [
            'commandes' => $commandes,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/livrer/{id}", name="acecommerce_logisticien_commande_livrer")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function livrer(Request $request, Commande $commande, EventDispatcherInterface $eventDispatcher)
    {
        if ($commande->isLivre()) {
            throw $this->createAccessDeniedException('La commande a déjà été traitée');
        }

        $form = $this->createForm(TraiterCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $event = new CommandeEvent($commande);

            $eventDispatcher->dispatch(CommandeEvent::COMMANDE_LIVRE, $event);

            $this->addFlash('success', 'La commande a bien été traitée');

            return $this->redirectToRoute('acecommerce_logisticien_commande_index');
        }

        return [
            'commande' => $commande,
            'form' => $form->createView(),
        ];
    }
}
