<?php

namespace App\Entity\Prix;

use App\Entity\InterfaceDef\PrixInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Prix\PrixRepository")
 */
class Prix implements PrixInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\Type("float")
     * @var float
     */
    private $htva;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @var float
     */
    private $promo_htva;

    /**
     * Non stocke en Bd
     * @var float
     */
    private $prix_tvac;

    /**
     * Non stocke en Bd
     * @var float
     */
    private $prix_promo_tvac;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getHtva(): ?float
    {
        return $this->htva;
    }

    /**
     * @param float $htva
     */
    public function setHtva(float $htva): void
    {
        $this->htva = $htva;
    }

    /**
     * @return float
     */
    public function getPromoHtva(): ?float
    {
        return $this->promo_htva;
    }

    /**
     * @param float|null $promo_htva
     */
    public function setPromoHtva(float $promo_htva): void
    {
        $this->promo_htva = $promo_htva;
    }

    /**
     * @return float
     */
    public function getPrixTvac(): ?float
    {
        return $this->prix_tvac;
    }

    /**
     * @param float $prix_tvac
     */
    public function setPrixTvac(float $prix_tvac): void
    {
        $this->prix_tvac = $prix_tvac;
    }

    /**
     * @return float
     */
    public function getPrixPromoTvac(): ?float
    {
        return $this->prix_promo_tvac;
    }

    /**
     * @param float $prix_promo_tvac
     */
    public function setPrixPromoTvac(float $prix_promo_tvac): void
    {
        $this->prix_promo_tvac = $prix_promo_tvac;
    }


}
