<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/09/17
 * Time: 15:25
 */

namespace App\Entity\Base;

use App\Entity\Commande\CommandeCout;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseCommande
 * @package App\Entity
 * ORM\Entity()//pour generation setter/getter
 */
abstract class BaseCommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $user;

    /**
     * Pour sauvegarde
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $commerce_nom;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $valide = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $cloture = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $paye = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $livre = 0;

    /**
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date_livraison;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $livraison_remarque;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $valide_remarque;

    /**
     * Pas stocke en bd
     *
     * @var CommandeCout
     */
    protected $cout;

    /**
     * @return CommandeCout
     */
    public function getCout()
    {
        return $this->cout;
    }

    /**
     * @param CommandeCout $cout
     */
    public function setCout(CommandeCout $cout)
    {
        $this->cout = $cout;
    }

    public function __toString()
    {
        return "commande ".$this->getId();
    }


    /**
     * STOP
     */


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getCommerceNom(): ?string
    {
        return $this->commerce_nom;
    }

    /**
     * @param string $commerce_nom
     */
    public function setCommerceNom(string $commerce_nom): void
    {
        $this->commerce_nom = $commerce_nom;
    }

    /**
     * @return bool
     */
    public function isValide(): bool
    {
        return $this->valide;
    }

    /**
     * @param bool $valide
     */
    public function setValide(bool $valide): void
    {
        $this->valide = $valide;
    }

    /**
     * @return bool
     */
    public function isCloture(): bool
    {
        return $this->cloture;
    }

    /**
     * @param bool $cloture
     */
    public function setCloture(bool $cloture): void
    {
        $this->cloture = $cloture;
    }

    /**
     * @return bool
     */
    public function isPaye(): bool
    {
        return $this->paye;
    }

    /**
     * @param bool $paye
     */
    public function setPaye(bool $paye): void
    {
        $this->paye = $paye;
    }

    /**
     * @return bool
     */
    public function isLivre(): bool
    {
        return $this->livre;
    }

    /**
     * @param bool $livre
     */
    public function setLivre(bool $livre): void
    {
        $this->livre = $livre;
    }

    /**
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->date_livraison;
    }

    /**
     * @param mixed $date_livraison
     */
    public function setDateLivraison($date_livraison): void
    {
        $this->date_livraison = $date_livraison;
    }

    /**
     * @return string
     */
    public function getLivraisonRemarque(): ?string
    {
        return $this->livraison_remarque;
    }

    /**
     * @param string $livraison_remarque
     */
    public function setLivraisonRemarque(string $livraison_remarque): void
    {
        $this->livraison_remarque = $livraison_remarque;
    }

    /**
     * @return string
     */
    public function getValideRemarque(): ?string
    {
        return $this->valide_remarque;
    }

    /**
     * @param string $valide_remarque
     */
    public function setValideRemarque(string $valide_remarque): void
    {
        $this->valide_remarque = $valide_remarque;
    }
}
