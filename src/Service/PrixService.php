<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 22/02/18
 * Time: 11:01
 */

namespace App\Service;


class PrixService
{
    /**
     * @param $prix
     * @return string
     */
    public function getRound($prix)
    {
        //return round($prix + 0.01) - 0.01;
        return number_format($prix, 2);
    }

    /**
     * Pour donner a stripe
     * @param $prix float
     * @return mixed
     */
    public function getInCent(float $prix)
    {
        return round($prix * 100);
    }
}