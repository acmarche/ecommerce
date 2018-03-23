<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/09/17
 * Time: 18:47
 */

namespace App\Entity\TraitDef;

use App\Entity\Client\Adresse;

trait AdresseFacturationTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client\Adresse")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL" )
     * @var Adresse|null
     */
    protected $adresse_facturation;

    /**
     * @return Adresse|null
     */
    public function getAdresseFacturation(): ?Adresse
    {
        return $this->adresse_facturation;
    }

    /**
     * @param Adresse|null $adresse_facturation
     */
    public function setAdresseFacturation(?Adresse $adresse_facturation): void
    {
        $this->adresse_facturation = $adresse_facturation;
    }

}