<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 13:52
 */

namespace App\Manager\InterfaceDef;

use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;

interface AttributManagerInterface
{
    /**
     * @param AttributInterface $attribut
     * @param string $nom
     * @param string $valeur
     * @return AttributInterface
     */
    public function create(AttributInterface $attribut, string $nom, string $valeur = null);

    /**
     * @param ListingAttributsInterface $listingAttributs
     * @return mixed
     */
    public function init(ListingAttributsInterface $listingAttributs);

    /**
     * @param AttributInterface $attribut
     * @return mixed
     */
    public function insert(AttributInterface $attribut);

    /**
     * @param AttributInterface $attribut
     * @return mixed
     */
    public function update(AttributInterface $attribut);
}