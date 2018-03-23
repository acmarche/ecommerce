<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 19:35
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\CommandeInterface;

trait CommandeTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande\Commande", inversedBy="commande_produits")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commande;

    /**
     * Set commande
     *
     * @param CommandeInterface $commande
     *
     */
    public function setCommande(CommandeInterface $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Get commerce
     *
     * @return CommandeInterface
     */
    public function getCommande()
    {
        return $this->commande;
    }
}