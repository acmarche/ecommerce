<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 5/09/17
 * Time: 16:21
 */

namespace App\Entity\Livraison;

use App\Entity\InterfaceDef\LieuLivraisonInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * dommerce
 *
 * @ORM\Table(name="lieu_livraison")
 * @ORM\Entity(repositoryClass="App\Repository\Livraison\LieuxLivraisonRepository")
 *
 */
class LieuLivraison implements LieuLivraisonInterface
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $rue;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $numero;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $code_postal;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $localite;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande\Commande", mappedBy="lieu_livraison", cascade={"persist"})
     *
     */
    protected $commandes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commerce\Commerce", inversedBy="lieu_livraison")
     * @ORM\JoinColumn(nullable=true)
     * @var
     */
    protected $commerce;

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * STOP
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return LieuLivraison
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return LieuLivraison
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return LieuLivraison
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return LieuLivraison
     */
    public function setCodePostal($codePostal)
    {
        $this->code_postal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set localite
     *
     * @param string $localite
     *
     * @return LieuLivraison
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return LieuLivraison
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
     * Add commande
     *
     * @param \App\Entity\Commande\Commande $commande
     *
     * @return LieuLivraison
     */
    public function addCommande(\App\Entity\Commande\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \App\Entity\Commande\Commande $commande
     */
    public function removeCommande(\App\Entity\Commande\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Set commerce
     *
     * @param \App\Entity\Commerce\Commerce $commerce
     *
     * @return LieuLivraison
     */
    public function setCommerce(\App\Entity\Commerce\Commerce $commerce = null)
    {
        $this->commerce = $commerce;

        return $this;
    }

    /**
     * Get commerce
     *
     * @return \App\Entity\Commerce\Commerce
     */
    public function getCommerce()
    {
        return $this->commerce;
    }
}
