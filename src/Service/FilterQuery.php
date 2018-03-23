<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 2/09/17
 * Time: 20:39
 */

namespace App\Service;

use App\Entity\Security\User;

class FilterQuery
{
    public function getAllCommerces(User $user)
    {
        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN'))
            return [];

        if ($user->hasRole('ROLE_ECOMMERCE_COMMERCE'))
            return ['user' => $user->getUsername()];

        if ($user->hasRole('ROLE_ECOMMERCE_LOGISTICIEN'))
            return [];

        if ($user->hasRole('ROLE_ECOMMERCE_CLIENT'))
            return [];

        return ['nom' => 'ddddd'];
    }

}