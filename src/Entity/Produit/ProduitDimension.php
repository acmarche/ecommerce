<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/12/17
 * Time: 13:10
 */

namespace App\Entity\Produit;

use App\Entity\Base\BaseEntity;
use App\Entity\TraitDef\ProduitTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Dimension
 * @package App\Entity
 *
 * @ORM\Table(name="produit_dimension")
 * @ORM\Entity(repositoryClass="App\Repository\Produit\ProduitDimensionRepository")
 *
 */
class ProduitDimension
{
    use ProduitTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Produit\Produit", inversedBy="dimension")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $produit;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $hauteur;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $largeur;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $longueur;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $poids;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $poids_unite;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $remarque;


    /**
     * Set hauteur
     *
     * @param string $hauteur
     *
     * @return ProduitDimension
     */
    public function setHauteur($hauteur)
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    /**
     * Get hauteur
     *
     * @return string
     */
    public function getHauteur()
    {
        return $this->hauteur;
    }

    /**
     * Set largeur
     *
     * @param string $largeur
     *
     * @return ProduitDimension
     */
    public function setLargeur($largeur)
    {
        $this->largeur = $largeur;

        return $this;
    }

    /**
     * Get largeur
     *
     * @return string
     */
    public function getLargeur()
    {
        return $this->largeur;
    }

    /**
     * Set longueur
     *
     * @param string $longueur
     *
     * @return ProduitDimension
     */
    public function setLongueur($longueur)
    {
        $this->longueur = $longueur;

        return $this;
    }

    /**
     * Get longueur
     *
     * @return string
     */
    public function getLongueur()
    {
        return $this->longueur;
    }

    /**
     * Set poids
     *
     * @param integer $poids
     *
     * @return ProduitDimension
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids
     *
     * @return integer
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set poidsUnite
     *
     * @param string $poidsUnite
     *
     * @return ProduitDimension
     */
    public function setPoidsUnite($poidsUnite)
    {
        $this->poids_unite = $poidsUnite;

        return $this;
    }

    /**
     * Get poidsUnite
     *
     * @return string
     */
    public function getPoidsUnite()
    {
        return $this->poids_unite;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     *
     * @return ProduitDimension
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }
}
