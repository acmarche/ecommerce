<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 12:11
 */

namespace App\Manager;

use App\Entity\Attribut\ListingAttributs;
use App\Entity\InterfaceDef\CommerceInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Manager\InterfaceDef\ListingAttributManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ListingAttributManager extends AbstractManager implements ListingAttributManagerInterface
{
    public function create(CommerceInterface $commerce)
    {
        $listing = new ListingAttributs();
        $listing->setCommerce($commerce);

        return $listing;
    }

    public function insert(ListingAttributsInterface $listingAttributs)
    {
        $this->entityManager->persist($listingAttributs);
        $this->flush();
    }

    public function update(ListingAttributsInterface $listingAttributs)
    {
        $this->flush();
    }

    /**
     * Quand on edit une liste
     * @param ListingAttributsInterface $listingAttributs
     * @return ArrayCollection
     */
    public function getOriginal(ListingAttributsInterface $listingAttributs)
    {
        $originalAttributs = new ArrayCollection();
        foreach ($listingAttributs->getAttributs() as $attribut) {
            $originalAttributs->add($attribut);
        }

        return $originalAttributs;
    }

    public function handlerEdit(ListingAttributsInterface $listingAttributs, $originalAttributs)
    {
        // remove the relationship between the tag and the Task
        foreach ($originalAttributs as $attribut) {

            if (false === $listingAttributs->getAttributs()->contains($attribut)) {
                // remove the Task from the Tag
                $listingAttributs->getAttributs()->removeElement($attribut);
                $this->entityManager->remove($attribut);
                // if it was a many-to-one relationship, remove the relationship like this
                //$attribut->setListingAttributs(null);

                //$entityManager->persist($attribut);

                // if you wanted to delete the Tag entirely, you can also do that
                //
            }
        }
    }
}