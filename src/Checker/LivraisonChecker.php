<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 22:40
 */

namespace App\Checker;


use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Manager\PanierManager;
use App\Service\Bottin;
use App\Service\LivraisonService;

class LivraisonChecker
{
    private $panierManager;
    private $bottinService;
    private $livraisonService;
    private $today;

    public function __construct(PanierManager $panierManager, Bottin $bottin, LivraisonService $livraisonService)
    {
        $this->panierManager = $panierManager;
        $this->bottinService = $bottin;
        $this->livraisonService = $livraisonService;
        $this->today = $this->livraisonService->getToday();
    }

    /**
     * @param \DateTime $dateLivraison
     * @throws \Exception
     */
    public function checkDateLivraison(\DateTime $dateLivraison)
    {
        if ($this->today->format('d-m-Y:H') > $dateLivraison->format('d-m-Y:H')) {
            throw new \Exception(
                'Date de livraison incorrecte'
            );
        }

        $this->checkHoraireCommerce($dateLivraison);
    }

    /**
     * @param \DateTime $dateLivraison
     * @throws \Exception
     */
    public function checkHoraireCommerce(\DateTime $dateLivraison)
    {
        foreach ($this->panierManager->getPanierEncours() as $commande) {
            $commerce = $commande->getCommerce();
            $commandeProduits = $commande->getCommandeProduits();

            $this->checkDelaiProduits($commandeProduits, $dateLivraison);

            $horaires = $this->bottinService->getHoraire($commerce->getBottinId());
            $dayNumber = $dateLivraison->format('w');//de 0 a 6

            foreach ($horaires as $horaire) {
                //on a une info sur ce jour la
                if ($dayNumber == $horaire->day) {
                    if ($horaire->is_closed == true) {
                        throw new \Exception(
                            'Le commerce'.$commerce.' est fermé à cette date, veuillez choisir une autre date de livraison'
                        );
                    }
                }
            }
        }
    }

    /**
     * @param CommandeProduitInterface[] $commandeProduits
     * @return bool
     * @throws \Exception
     */
    public function checkDelaiProduits($commandeProduits, \DateTime $dateLivraison)
    {
        foreach ($commandeProduits as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            if ($produit->isDelai24h()) {
                throw new \Exception(
                    'Le produit'.$produit.' demande un délais de livraison plus important'
                );
            }
        }

        return true;
    }
}