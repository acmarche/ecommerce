<?php

namespace App\Controller;

use App\Entity\Commande\Commande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Event\CommandeEvent;
use App\Service\CommandeCoutService;
use App\Service\StripeService;
use App\Service\TvaService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Stripe\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaiementController
 * @package App\Controller
 * @Route("/paiement")
 * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
 */
class PaiementController extends AbstractController
{
    /**
     * @var TvaService
     */
    private $tvaService;

    public function __construct(TvaService $tvaService)
    {
        $this->tvaService = $tvaService;
    }

    /**
     *
     * @Route("/validation/{id}", name="acecommerce_paiement_validation")
     * @Method("POST")
     * @Security("is_granted('show', commande)")
     */
    public function validation(
        Request $request,
        Commande $commande,
        CommandeCoutService $commandeCoutService,
        StripeService $stripeUtil,
        EventDispatcherInterface $eventDispatcher
    ) {
        $em = $this->getDoctrine()->getManager();

        $stripeToken = $request->request->get('stripeToken');
        $stripeTokenType = $request->request->get('stripeTokenType');
        $stripeEmail = $request->request->get('stripeEmail');
        $clientIp = $request->getClientIp();

        $commande->setCout($commandeCoutService->getCoutsCommande($commande));

        try {
            $charge = $stripeUtil->chargeCard($stripeToken, $commande, $stripeEmail);
            $commande->setPaye(true);
            $em->flush();
            $this->finalyseComande($commande);

            $this->addFlash('success', 'Paiement effectué');

            $stripeUtil->createEntityCharge(
                $charge,
                $commande,
                $stripeToken,
                $stripeTokenType,
                $stripeEmail,
                $clientIp
            );

            $event = new CommandeEvent($commande);
            $eventDispatcher->dispatch(CommandeEvent::COMMANDE_PAYE, $event);

        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('acecommerce_livraison_paiement');
        }

        return $this->redirectToRoute('acecommerce_livraison_paiement');
    }

    protected function finalyseComande(CommandeInterface $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $commande->setCommerceNom($commande->getCommerce()->getNom());
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $commandeProduit->setTvaApplique($this->tvaService->getTvaApplicable($commandeProduit->getProduit()));
            $commandeProduit->setPrixHtva($commandeProduit->getProduit()->getPrixHtva());
            $commandeProduit->setProduitNom($commandeProduit->getProduit()->getNom());
        }

        $em->persist($commande);
        $em->flush();
    }
}
