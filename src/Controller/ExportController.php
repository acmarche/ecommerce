<?php

namespace App\Controller;

use App\Entity\Commande\Commande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Service\Bottin;
use App\Service\CommandeCoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Export controller.
 *
 * @Route("/facture")
 *
 */
class ExportController extends AbstractController
{
    /**
     *
     * @Route("/commande/{id}", name="acecommerce_commande_facture_pdf")
     * @Method("GET")
     * @Security("is_granted('show', commande)")
     */
    public function pdf(
        Commande $commande = null,
        CommandeCoutService $commandeUtil,
        Bottin $bottin
    ) {
        $html = $this->getHttm($commande, $commandeUtil, $bottin);

        $name = 'commande '.$commande->getId();

        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('footer-right', '[page]/[toPage]');

        return new Response(
            $snappy->getOutputFromHtml($html), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$name.'.pdf"',
            )
        );
    }

    protected function getHttm(CommandeInterface $commande, CommandeCoutService $commandeUtil, Bottin $bottin)
    {
        $html = $this->renderView(
            'facture/_head.html.twig',
            array(
                'title' => 'facture',
            )
        );

        $fiche = false;
        $commerce = $commande->getCommerce();
        $couts = $commandeUtil->getCoutsCommande($commande);
        try {
            $fiche = $bottin->getFiche($commerce->getBottinId());
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $html .= $this->renderView(
            'facture/facture.html.twig',
            array(
                'commerce' => $commerce,
                'fiche' => $fiche,
                'commande' => $commande,
                'couts' => $couts,
                'pdf' => true,
                'user' => $this->getUser(),
            )
        );

        $html .= $this->renderView('facture/_foot.html.twig', array());

        return $html;
    }
}
