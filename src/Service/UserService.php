<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 26/02/18
 * Time: 14:29
 */

namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentUser()
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $user = $token->getUser();
            //a cause anonyme user
            if (!$user instanceof UserInterface) {
                $user = null;
            }
        }

        return $user;
    }
}