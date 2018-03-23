<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 13:48
 */

namespace App\Entity\InterfaceDef;

use Doctrine\Common\Collections\ArrayCollection;

interface ListingAttributsInterface
{
    public function __toString();

    public function addAttribut(AttributInterface $attribut);

    public function removeAttribut(AttributInterface $attribut);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     */
    public function setId($id): void;

    /**
     * @return string
     */
    public function getNom(): ?string;

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void;

    /**
     * @return ArrayCollection|AttributInterface[]
     */
    public function getAttributs();

    /**
     * @param AttributInterface[] $attributs
     */
    public function setAttributs($attributs): void;

    /**
     * @param CommerceInterface $commerce
     * @return CommerceInterface
     */
    public function setCommerce(CommerceInterface $commerce);

    /**
     * Get commerce
     *
     * @return CommerceInterface
     */
    public function getCommerce();

}