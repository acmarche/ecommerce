<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/12/17
 * Time: 15:03
 */

namespace App\Entity\Produit;

use App\Entity\Base\BaseImage;
use App\Entity\TraitDef\ProduitTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitImage
 *
 * @ORM\Table(name="produit_image")
 * @ORM\Entity(repositoryClass="App\Repository\Produit\ProduitImageRepository")
 */
class ProduitImage extends BaseImage
{
    use ProduitTrait;

    /**
     * Overload inversedBy
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit\Produit", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var
     */
    protected $produit;

    public function __construct(Produit $produit, $imageName)
    {
        $this->setProduit($produit);
        $this->setName($imageName);
    }

}
