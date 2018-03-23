<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:00
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\CategoryInterface;
use App\Entity\InterfaceDef\CommerceInterface;
use Doctrine\ORM\Mapping as ORM;

trait CommerceTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commerce\Commerce", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * Set commerce
     *
     * @param CategoryInterface $commerce
     */
    public function setCommerce(CommerceInterface $commerce)
    {
        $this->commerce = $commerce;
    }

    /**
     * Get commerce
     *
     * @return CommerceInterface
     */
    public function getCommerce()
    {
        return $this->commerce;
    }

}
