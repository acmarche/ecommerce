<?php

namespace App\Event;

use App\Entity\Produit\Produit;
use App\Service\EcommerceConstante;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 12:05
 */
class ProduitEvent extends Event
{
    const PRODUIT_NEW = EcommerceConstante::PRODUIT_NEW;

    protected $produit;

    public function __construct(Produit $produit)
    {
        $this->produit = $produit;
    }

    public function getProduit()
    {
        return $this->produit;
    }
}