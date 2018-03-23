<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:40
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\CommandeProduitInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

trait CommandeProduitTrait
{
    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Commande\CommandeProduit", mappedBy="commande", cascade={"persist", "remove"},fetch="EAGER")
     *
     * @MaxDepth(2)
     *
     */
    protected $commande_produits;


    /**
     * Add commandeProduit
     *
     * @param CommandeProduitInterface $commandeProduit
    *
     */
    public function addCommandeProduit(CommandeProduitInterface $commandeProduit)
    {
        $this->commande_produits[] = $commandeProduit;
    }

    /**
     * Remove commandeProduit
     *
     * @param CommandeProduitInterface $commandeProduit
     */
    public function removeCommandeProduit(CommandeProduitInterface $commandeProduit)
    {
        $this->commande_produits->removeElement($commandeProduit);
    }

    /**
     * Get commandeProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandeProduits()
    {
        return $this->commande_produits;
    }

}