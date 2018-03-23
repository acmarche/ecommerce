<?php

namespace App\Security\Voter;

use App\Entity\InterfaceDef\CommerceInterface;
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
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class CommerceVoter extends Voter
{
    private $decisionManager;

    const ADD_SUPPLEMENT = 'addsupplement';
    const ADD_PRODUIT = 'addproduit';
    const ADD_INGREDIENT = 'addingredient';
    const ADD_LISTING = 'addlisting';
    const SHOW = 'show';
    const EDIT = 'edit';
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
        return $subject instanceof CommerceInterface && in_array(
                $attribute,
                [
                    self::ADD_SUPPLEMENT,
                    self::ADD_PRODUIT,
                    self::ADD_INGREDIENT,
                    self::ADD_LISTING,
                    self::ADD_PRODUIT,
                    self::SHOW,
                    self::EDIT,
                    self::DELETE,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $commerce, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_ADMIN'])) {
            return true;
        }

        switch ($attribute) {
            case self::ADD_SUPPLEMENT:
                return $this->canAddSupplement($commerce, $token);
            case self::ADD_INGREDIENT:
                return $this->canAddIngredient($commerce, $token);
            case self::ADD_PRODUIT:
                return $this->canAddProduit($commerce, $token);
            case self::ADD_LISTING:
                return $this->canAddListing($commerce, $token);
            case self::SHOW:
                return $this->canView($commerce, $token);
            case self::EDIT:
                return $this->canEdit($commerce, $token);
            case self::DELETE:
                return $this->canDelete($commerce, $token);
        }

        return false;
    }

    private function canAddProduit(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->canEdit($commerce, $token)) {
            return true;
        }

        return false;
    }

    private function canAddIngredient(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->canEdit($commerce, $token)) {
            return true;
        }

        return false;
    }

    private function canAddSupplement(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->canEdit($commerce, $token)) {
            return true;
        }

        return false;
    }

    private function canAddListing(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->canEdit($commerce, $token)) {
            return true;
        }

        return false;
    }

    private function canView(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->canEdit($commerce, $token)) {
            return true;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_LOGISTICIEN'])) {
            return true;
        }

        return false;
    }

    private function canEdit(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {
            $user = $token->getUser();

            if ($commerce->getUser() == $user->getUsername()) {
                return true;
            }
        }

        return false;
    }

    private function canDelete(CommerceInterface $commerce, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_ADMIN'])) {
            return true;
        }

        return false;
    }

}
