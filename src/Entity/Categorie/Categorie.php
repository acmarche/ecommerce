<?php

namespace App\Entity\Categorie;

use App\Entity\Base\BaseEntity;
use App\Entity\InterfaceDef\CategoryInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="App\Repository\Categorie\CategorieRepository")
 *
 */
class Categorie extends BaseEntity implements CategoryInterface
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $system = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Categorie\Categorie", mappedBy="parent", cascade={"remove"})
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie\Categorie", inversedBy="children", cascade={"remove"})
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produit\Produit", mappedBy="categorie")
     *
     * @var
     */
    protected $produits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Categorie\CategorieImage", mappedBy="categorie", cascade={"persist", "detach"})
     * @ORM\OrderBy({"position": "ASC"})
     */
    protected $images;

    public function __toString()
    {
        return $this->getNom();
    }

    public function getFirstImage()
    {
        return isset($this->getImages()[0]) ? $this->getImages()[0] : null;
    }

    /**
     * @param $images CategorieImage[]
     */
    public function setImages($images)
    {
        foreach ($images as $image) {
            $this->addImage($image);
        }
    }

    /**
     * STOP
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set system
     *
     * @param boolean $system
     *
     * @return Categorie
     */
    public function setSystem($system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return boolean
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Categorie
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
     * Add child
     *
     * @param \App\Entity\Categorie\Categorie $child
     *
     * @return Categorie
     */
    public function addChild(\App\Entity\Categorie\Categorie $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \App\Entity\Categorie\Categorie $child
     */
    public function removeChild(\App\Entity\Categorie\Categorie $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \App\Entity\Categorie\Categorie $parent
     *
     * @return Categorie
     */
    public function setParent(\App\Entity\Categorie\Categorie $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \App\Entity\Categorie\Categorie
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add produit
     *
     * @param \App\Entity\Produit\Produit $produit
     *
     * @return Categorie
     */
    public function addProduit(\App\Entity\Produit\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \App\Entity\Produit\Produit $produit
     */
    public function removeProduit(\App\Entity\Produit\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Add image
     *
     * @param \App\Entity\Categorie\CategorieImage $image
     *
     * @return Categorie
     */
    public function addImage(\App\Entity\Categorie\CategorieImage $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \App\Entity\Categorie\CategorieImage $image
     */
    public function removeImage(\App\Entity\Categorie\CategorieImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
}
