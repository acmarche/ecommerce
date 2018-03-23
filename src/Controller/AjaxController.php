<?php

namespace App\Controller;

use App\Entity\Commande\Commande;
use App\Service\CommandeCoutService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/***********************
 * THIS FILE IS NOT USE
 * *********************
 */


/**
 * Class AjaxController
 * @Route("/ajax")
 *
 */
class AjaxController extends AbstractController
{
    /**
     * @Route("/commande/{id}", name="acecommerce_ajax_getcommande")
     * @todo secure
     */
    public function commande(Commande $commande, CommandeCoutService $commandeUtil)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if ($commande->getUser() != $user->getUsername()) {
            return new JsonResponse(['error' => 'Interdit']);
        }

        $couts = $commandeUtil->getCoutsCommande($commande);

        return new JsonResponse($couts);
    }

    /**
     * @Route("/resume", name="acecommerce_panier_resume")
     * @Method("GET")
     */
    public function resume(CommandeCoutService $commandeUtil)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(Commande::class)->getPanier($user);

        $totalTvac = $countProduits = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                foreach ($commande->getCommandeProduits() as $commandeProduit) {
                    $countProduits += $commandeProduit->getQuantite();
                }
                if ($countProduits > 0) {
                    $couts = $commandeUtil->getCoutsCommande($commande);
                    $totalTvac += $couts['totalTvac'];
                }
            }
        }

        return $this->render(
            '@AcMarcheLunch/panier/_resume.html.twig',
            [
                'totalTvac' => $totalTvac,
                'nombreArticles' => $countProduits,
            ]
        );
    }

    /**
     * @Route("/list", name="acecommerce_panier_list")
     * @Method("GET")
     */
    public function list2()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(Commande::class)->getPanier($user);
        $commandeProduits = [];
        $countProduits = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                $countProduits += count($commande->getCommandeProduits());
                foreach ($commande->getCommandeProduits() as $commandeProduit) {
                    $commandeProduits[] = $commandeProduit;
                }
            }
        }

        return $this->render(
            '@AcMarcheLunch/panier/_list.html.twig',
            [
                'nombreProduits' => $countProduits,
                'commandeProduits' => $commandeProduits,
            ]
        );
    }


}
