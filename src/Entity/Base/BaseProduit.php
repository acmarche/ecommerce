<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/09/17
 * Time: 19:22
 */

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseProduit
 * @package App\Entity
 * ORM\Entity() //pour generation setter/getter
 */
abstract class BaseProduit extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * Retirer de la vente le produit
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $indisponible = false;

    /**
     * Lunch ou pas
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $isFood = false;

    /**
     * -1 = infinis
     * @var float
     *
     * @ORM\Column(type="integer")
     */
    protected $quantite_stock = -1;

    /**
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    protected $delai_24h = false;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $tva_applicable;

    public function isOutOfStock()
    {
        if ($this->getQuantiteStock() == 0) {
            return true;
        }

        return false;
    }

    public function __toString()
    {
        return $this->getNom();
    }


    /**
     * STOP
     */

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return BaseProduit
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return BaseProduit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return BaseProduit
     */
    public function setIndisponible($indisponible)
    {
        $this->indisponible = $indisponible;

        return $this;
    }

    /**
     * Get indisponible
     *
     * @return boolean
     */
    public function getIndisponible()
    {
        return $this->indisponible;
    }

    /**
     * Set isFood
     *
     * @param boolean $isFood
     *
     * @return BaseProduit
     */
    public function setIsFood($isFood)
    {
        $this->isFood = $isFood;

        return $this;
    }

    /**
     * Get isFood
     *
     * @return boolean
     */
    public function getIsFood()
    {
        return $this->isFood;
    }

    /**
     * Set quantiteStock
     *
     * @param integer $quantiteStock
     *
     * @return BaseProduit
     */
    public function setQuantiteStock($quantiteStock)
    {
        $this->quantite_stock = $quantiteStock;

        return $this;
    }

    /**
     * Get quantiteStock
     *
     * @return integer
     */
    public function getQuantiteStock()
    {
        return $this->quantite_stock;
    }

    /**
     * Set tvaApplicable
     *
     * @param string $tvaApplicable
     *
     * @return BaseProduit
     */
    public function setTvaApplicable($tvaApplicable)
    {
        $this->tva_applicable = $tvaApplicable;

        return $this;
    }

    /**
     * Get tvaApplicable
     *
     * @return string
     */
    public function getTvaApplicable()
    {
        return $this->tva_applicable;
    }

    /**
     * @return bool
     */
    public function isDelai24h(): bool
    {
        return $this->delai_24h;
    }

    /**
     * @param bool $delai_24h
     */
    public function setDelai24h(bool $delai_24h): void
    {
        $this->delai_24h = $delai_24h;
    }

}
