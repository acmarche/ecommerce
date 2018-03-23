<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 22/02/18
 * Time: 15:19
 */

namespace App\Entity\Commande;

/**
 * Couts PAR COMMANDE
 * Class CommandeCout
 * @package App\Entity\Commande
 */
class CommandeCout
{
    /**
     *
     * @var float
     */
    private $montantTva;

    /**
     * @var float
     */
    private $totalHtva;

    /**
     * @var float
     */
    private $totalTvac;

    /**
     * @var float
     */
    private $fraisTransaction;

    /**
     * @var float
     */
    private $a_payer;

    /**
     * @var float
     */
    private $total_in_cents;

    /**
     * @var float|null
     */
    private $attributsTvac;

    /**
     * @var float|null
     */
    private $attributsHtva;

    /**
     * @return float
     */
    public function getMontantTva(): float
    {
        return $this->montantTva;
    }

    /**
     * @param float $montantTva
     */
    public function setMontantTva(float $montantTva): void
    {
        $this->montantTva = $montantTva;
    }

    /**
     * @return float
     */
    public function getTotalHtva(): float
    {
        return $this->totalHtva;
    }

    /**
     * @param float $totalHtva
     */
    public function setTotalHtva(float $totalHtva): void
    {
        $this->totalHtva = $totalHtva;
    }

    /**
     * @return float
     */
    public function getTotalTvac(): float
    {
        return $this->totalTvac;
    }

    /**
     * @param float $totalTvac
     */
    public function setTotalTvac(float $totalTvac): void
    {
        $this->totalTvac = $totalTvac;
    }

    /**
     * @return float
     */
    public function getFraisTransaction(): float
    {
        return $this->fraisTransaction;
    }

    /**
     * @param float $fraisTransaction
     */
    public function setFraisTransaction(float $fraisTransaction): void
    {
        $this->fraisTransaction = $fraisTransaction;
    }

    /**
     * @return float
     */
    public function getAPayer(): ?float
    {
        return $this->a_payer;
    }

    /**
     * @param float $a_payer
     */
    public function setAPayer(float $a_payer): void
    {
        $this->a_payer = $a_payer;
    }

    /**
     * @return float
     */
    public function getTotalInCents(): ?float
    {
        return $this->total_in_cents;
    }

    /**
     * @param float $total_in_cents
     */
    public function setTotalInCents(float $total_in_cents): void
    {
        $this->total_in_cents = $total_in_cents;
    }

    /**
     * @return float|null
     */
    public function getAttributsTvac(): ?float
    {
        return $this->attributsTvac;
    }

    /**
     * @param float|null $attributsTvac
     */
    public function setAttributsTvac(?float $attributsTvac): void
    {
        $this->attributsTvac = $attributsTvac;
    }

    /**
     * @return float|null
     */
    public function getAttributsHtva(): ?float
    {
        return $this->attributsHtva;
    }

    /**
     * @param float|null $attributsHtva
     */
    public function setAttributsHtva(?float $attributsHtva): void
    {
        $this->attributsHtva = $attributsHtva;
    }

}