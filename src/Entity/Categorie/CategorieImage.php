<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/12/17
 * Time: 15:03
 */

namespace App\Entity\Categorie;

use App\Entity\Base\BaseImage;
use App\Entity\TraitDef\CategoryTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieImage
 *
 * @ORM\Table(name="categorie_image")
 * @ORM\Entity(repositoryClass="App\Repository\Categorie\CategorieImageRepository")
 */
class CategorieImage extends BaseImage
{
    use CategoryTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie\Categorie", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $categorie;

    public function __construct(Categorie $categorie, $imageName)
    {
        $this->setCategorie($categorie);
        $this->setName($imageName);
    }

}
