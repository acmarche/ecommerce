<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace App\Entity\InterfaceDef;


interface CategoryInterface
{
    public function __toString();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getImages();

}