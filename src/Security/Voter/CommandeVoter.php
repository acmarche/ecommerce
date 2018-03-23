<?php

namespace App\Security\Voter;

use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\Security\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * It grants or denies permissions for actions related to blog posts (such as
 * showing, editing and deleting posts).
 *
 * See http://symfony.com/doc/current/security/voters.html
 *
 */
class CommandeVoter extends Voter
{
    private $decisionManager;
    const ADD = 'new';
    const SHOW = 'show';
    const EDIT = 'edit';
    const VALIDATE = 'validate';
    const DELETE = 'delete';

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        // this voter is only executed for three specific permissions on Post objects
        return $subject instanceof CommandeInterface && in_array(
                $attribute,
                [self::ADD, self::SHOW, self::EDIT, self::VALIDATE, self::DELETE]
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $commande, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_ADMIN'])) {
            return true;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->canView($commande, $token);
            case self::EDIT:
                return $this->canEdit($commande, $token);
            case self::VALIDATE:
                return $this->canValidate($commande, $token);
            case self::DELETE:
                return $this->canDelete($commande, $token);
        }

        return false;
    }

    /**
     *
     * @param CommandeInterface $commande
     * @param User $user
     * @return bool
     */
    private function canView(CommandeInterface $commande, TokenInterface $token)
    {
        $user = $token->getUser();

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {
            return $this->commerceCanAccess($commande, $token);
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_LOGISTICIEN'])) {
            return $commande->isPaye();
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_CLIENT'])) {
            if ($commande->getUser() == $user->getUsername()) {
                return true;
            }

        }

        return false;
    }

    /**
     * @param CommandeInterface $commande
     * @param User $user
     * @return bool
     */
    private function canEdit(CommandeInterface $commande, TokenInterface $token)
    {
        //on ne modifie pas une commande paye
        if ($commande->isPaye()) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {
            return $this->commerceCanAccess($commande, $token);
        }

        return false;
    }

    /**
     * @param CommandeInterface $commande
     * @param User $user
     * @return bool
     */
    private function canDelete(CommandeInterface $commande, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {
            return $this->commerceCanAccess($commande, $token);
        }

        return false;
    }

    /**
     * @param CommandeInterface $commande
     * @param User $user
     * @return bool
     */
    private function canValidate(CommandeInterface $commande, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {
            return $this->commerceCanAccess($commande, $token);
        }

        return false;
    }

    private function commerceCanAccess(CommandeInterface $commande, TokenInterface $token)
    {
        $user = $token->getUser();

        $commerce = $commande->getCommerce();
        if (!$commerce) {
            return false;
        }

        if ($commerce->getUser() == $user->getUsername()) {
            return true;
        }

        return false;
    }
}
