<?php

namespace App\Controller;

use App\Entity\Commande\Commande;
use App\Service\CommandeCoutService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * commande controller.
 *
 * @Route("/commande")
 *
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="acecommerce_commande_index")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     */
    public function index(CommandeCoutService $commandeCoutService)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $args = ['user' => $user, 'paye' => true];
        $commandes = $em->getRepository(Commande::class)->search($args);
        $commandeCoutService->bindCouts($commandes);

        return [
            'user' => $user,
            'commandes' => $commandes,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="acecommerce_commande_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('show', commande)")
     */
    public function show(Commande $commande, CommandeCoutService $commandeUtil)
    {
        $commandeUtil->bindCouts([$commande]);

        return [
            'commande' => $commande,
        ];
    }

}
