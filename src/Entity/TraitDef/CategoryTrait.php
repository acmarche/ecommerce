<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:00
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\CategoryInterface;
use Doctrine\ORM\Mapping as ORM;

trait CategoryTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie\Categorie", inversedBy="produits")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $categorie;

    /**
     * Set categorie
     *
     * @param CategoryInterface $categorie
     *
     * @return CategoryInterface
     */
    public function setCategorie(CategoryInterface $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return CategoryInterface
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

}
