<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 2/03/18
 * Time: 13:57
 */

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker
 * https://symfony.com/doc/current/security/user_checkers.html
 * @package App\Security
 */
class UserChecker implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        // TODO: Implement checkPreAuth() method.
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        }
        // TODO: Implement checkPostAuth() method.
    }
}