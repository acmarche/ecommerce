<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/2018
 * Time: 18:47
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\PrixInterface;
use App\Entity\Prix\Prix;

trait PrixTrait
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Prix\Prix", cascade={"persist","remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @var Prix
     */
    private $prix;

    /**
     * @return PrixInterface
     */
    public function getPrix(): ?PrixInterface
    {
        return $this->prix;
    }

    /**
     * @param PrixInterface $prix
     */
    public function setPrix(PrixInterface $prix): void
    {
        $this->prix = $prix;
    }

}