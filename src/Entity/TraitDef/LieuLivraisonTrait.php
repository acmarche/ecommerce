<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/09/17
 * Time: 18:47
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\CommandeInterface;

trait LieuLivraisonTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Livraison\LieuLivraison", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL" )
     * @var
     */
    protected $lieu_livraison;

    /**
     * Set lieuLivraison
     *
     * @param \App\Entity\Livraison\LieuLivraison $lieuLivraison
     *
     * @return CommandeInterface
     */
    public function setLieuLivraison(\App\Entity\Livraison\LieuLivraison $lieuLivraison = null)
    {
        $this->lieu_livraison = $lieuLivraison;

        return $this;
    }

    /**
     * Get lieuLivraison
     *
     * @return \App\Entity\Livraison\LieuLivraison
     */
    public function getLieuLivraison()
    {
        return $this->lieu_livraison;
    }
}