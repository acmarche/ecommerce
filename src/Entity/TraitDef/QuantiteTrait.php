<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/2018
 * Time: 18:47
 */

namespace App\Entity\TraitDef;

use App\Entity\Quantite\Quantite;

trait QuantiteTrait
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quantite\Quantite", cascade={"persist","remove"})
     * @var Quantite
     */
    private $quantite;

    /**
     * @return Quantite
     */
    public function getQuantite(): ?Quantite
    {
        return $this->quantite;
    }

    /**
     * @param Quantite $quantite
     */
    public function setQuantite(Quantite $quantite): void
    {
        $this->quantite = $quantite;
    }

}