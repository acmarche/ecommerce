<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 26/02/18
 * Time: 16:38
 */

namespace App\Service;

use App\Manager\PanierManager;

class LivraisonService
{
    private $panierManager;
    private $bottinService;
    /**
     * @var \DateTime
     */
    private $today;

    public function __construct(PanierManager $panierManager, Bottin $bottin)
    {
        $this->panierManager = $panierManager;
        $this->bottinService = $bottin;
    }

    /**
     * @required
     * @param \DateTime|null $date
     */
    public function setToday(\DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
        }

        $this->today = $date;
    }

    /**
     * Depuis form je n'ai pas l'heure
     * @param \DateTime $dateTime
     * @return bool|\DateTime
     */
    public function setHeure(\DateTime $dateTime)
    {
        return \DateTime::createFromFormat(
            'd-m-Y',
            $dateTime->format('d-m-Y')
        );
    }

    /**
     * Retourne la date de livraison la plus proche possible
     * @return \DateTime|static
     */
    public function getDateProchaineLivraison()
    {
        $hour = $this->today->format('G');//de 0 a 23

        //todo jours feries
        if ($hour > 10) {
            return $this->today->modify('+1 day');
        }

        //we
        $dayNumber = $this->today->format('w');//de 0 a 6
        if ($dayNumber > 4) {
            $this->today->modify('+2 day');
        }

        return $this->today;
    }

    /**
     * @return \DateTime
     */
    public function getToday()
    {
        return $this->today;
    }
}