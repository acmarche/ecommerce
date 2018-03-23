<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 13:52
 */

namespace App\Manager\InterfaceDef;

use App\Entity\InterfaceDef\CommerceInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;

interface ListingAttributManagerInterface
{
    /**
     * @param CommerceInterface $commerce
     * @return ListingAttributsInterface
     */
    public function create(CommerceInterface $commerce);

    /**
     * @param ListingAttributsInterface $listingAttributs
     * @return mixed
     */
    public function insert(ListingAttributsInterface $listingAttributs);

    /**
     * @param ListingAttributsInterface $listingAttributs
     * @return mixed
     */
    public function update(ListingAttributsInterface $listingAttributs);

    public function getOriginal(ListingAttributsInterface $listingAttributs);

    public function handlerEdit(ListingAttributsInterface $listingAttributs, $originalAttributs);
}