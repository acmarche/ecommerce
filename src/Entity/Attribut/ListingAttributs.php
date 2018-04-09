<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/03/18
 * Time: 21:46
 */

namespace App\Entity\Attribut;

use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Entity\TraitDef\CommerceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Attribut\ListingAttributsRepository")
 */
class ListingAttributs implements ListingAttributsInterface, JsonSerializable
{
    use CommerceTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attribut\Attribut",  fetch="EXTRA_LAZY", mappedBy="listingAttributs", cascade={"persist","remove"})
     * @var Attribut[]
     */
    private $attributs;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commerce\Commerce", inversedBy="listing_attributs")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * ListAttribut constructor.
     */
    public function __construct()
    {
        $this->attributs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function addAttribut(AttributInterface $attribut)
    {
        // for a many-to-many association:
        //$attribut->addTask($this);

        // for a many-to-one association:
        $attribut->setListingAttributs($this);

        $this->attributs->add($attribut);
    }

    public function removeAttribut(AttributInterface $attribut)
    {
        $this->attributs->removeElement($attribut);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return ArrayCollection|AttributInterface[]
     */
    public function getAttributs()
    {
        return $this->attributs;
    }

    /**
     * @param AttributInterface[] $attributs
     */
    public function setAttributs($attributs): void
    {
        $this->attributs = $attributs;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        //Les collections Doctrines sont chargées de manière lazy,
        //Une array est créée pour sérialiser les objets de manière forcée
        $arrayAttributs = array();
        foreach ($this->getAttributs() as $attribut) {
            array_push($arrayAttributs, $attribut);
        }

        return[
            'id'=>$this->getId(),
            'nom'=>$this->getNom(),
            'attributs'=>$arrayAttributs
        ];
    }
}