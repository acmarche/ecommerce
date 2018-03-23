<?php

namespace App\Controller\Admin;

use App\Entity\Commande\Commande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Service\CommandeCoutService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Export controller.
 *
 * @Route("/export")
 *
 */
class ExportController extends Controller
{
    /**
     *
     * @Route("/pdf/commande/{id}", name="acecommerce_admin_commande_export_pdf")
     * @Method("GET")
     * @Security("is_granted('show', commande)")
     */
    public function pdf(Request $request, Commande $commande = null, CommandeCoutService $commandeCoutService)
    {
        $html = $this->getHttm($request, $commande, $commandeCoutService);

        $name = 'commandes';

        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('footer-right', '[page]/[toPage]');

        return new Response(
            $snappy->getOutputFromHtml($html), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$name.'.pdf"',
            )
        );
    }

    protected function getHttm(Request $request, CommandeInterface $commande = null, CommandeCoutService $commandeUtil)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $args = [];
        $key = "commande_search";

        if ($commande) {
            $commandes = [$commande];
        } else {
            if ($session->has($key)) {
                $args = unserialize($session->get($key));
            } else {
                $args = [];
            }//commande du jour
            $commandes = $em->getRepository(Commande::class)->search($args);
        }

        $html = $this->renderView(
            'admin/pdf/head.html.twig',
            array(
                'title' => 'Liste des interventions',
            )
        );

        foreach ($commandes as $commande) {
            $couts = $commandeUtil->getCoutsCommande($commande);

            $html .= $this->renderView(
                'admin/pdf/line.html.twig',
                array(
                    'commande' => $commande,
                    'couts' => $couts,
                    'pdf' => true,
                )
            );
        }
        $html .= $this->renderView('admin/pdf/foot.html.twig', array());

        return $html;
    }
}
