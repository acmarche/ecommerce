<?php

namespace App\Controller;

use App\Checker\LivraisonChecker;
use App\Entity\Commande\Commande;
use App\Form\Livraison\LivraisonType;
use App\Manager\PanierManager;
use App\Service\CommandeCoutService;
use App\Service\LivraisonService;
use App\Service\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LivraisonController
 * @package App\Controller
 * @Route("/livraison")
 * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/", name="acecommerce_livraison")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function index(
        Request $request,
        PanierManager $panierManager,
        LivraisonService $livraisonService,
        LivraisonChecker $livraisonChecker
    ) {
        $em = $this->getDoctrine()->getManager();

        $dateProchaineLivraison = $livraisonService->getDateProchaineLivraison();
        $commandes = $panierManager->getPanierEncours();

        if (count($commandes) == 0) {
            return $this->redirectToRoute('acecommerce_home');
        }

        $form = $this->createForm(LivraisonType::class, ['dateLivraison' => $dateProchaineLivraison]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $dateLivraisonSelectionne = $livraisonService->setHeure($data['dateLivraison']);

            $lieuLivraison = $data['lieuLivraison'];
            $adresseFacturation = $data['adresse'];

            try {
                $livraisonChecker->checkDateLivraison($dateLivraisonSelectionne);
                foreach ($commandes as $commande) {
                    $commande->setDateLivraison($dateLivraisonSelectionne);
                    $commande->setLieuLivraison($lieuLivraison);
                    $commande->setAdresseFacturation($adresseFacturation);
                    $em->persist($commande);
                    $em->flush();

                    return $this->redirectToRoute('acecommerce_livraison_paiement');
                }

            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirectToRoute('acecommerce_livraison');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/paiement", name="acecommerce_livraison_paiement")
     * @Template()
     */
    public function paiement(CommandeCoutService $commandeCoutService, StripeService $stripeService)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getPanier($user);

        foreach ($commandes as $commande) {
            $commande->setCout($commandeCoutService->getCoutsCommande($commande));
        }

        $publicKey = $stripeService->getStripePublic();

        return [
            'publicKey' => $publicKey,
            'commandes' => $commandes,
        ];
    }


}
