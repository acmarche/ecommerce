<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 21:43
 */

namespace App\Checker;


class QuantiteChecker
{
    /**
     * Valeur quantite
     * @param $quantite
     * @return bool
     */
    public function valueQuantiteIsReal($quantite)
    {
        if ($quantite < 1) {
            return false;
        }

        return true;
    }
}