<?php

namespace App\Security\Voter;

use App\Entity\InterfaceDef\ProduitInterface;
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
class ProduitVoter extends Voter
{
    const ADD = 'new';
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';
    private $decisionManager;

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
        return $subject instanceof ProduitInterface && in_array($attribute, [self::ADD, self::SHOW, self::EDIT, self::DELETE]);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $produit, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_ADMIN']))
            return true;

        switch ($attribute) {
            case self::SHOW:
                return $this->canView($produit, $token);
            case self::ADD:
                return $this->canAdd($produit, $token);
            case self::EDIT:
                return $this->canEdit($produit, $token);
            case self::DELETE:
                return $this->canDelete($produit, $token);
        }

        return false;
    }

    private function canView(ProduitInterface $produit, TokenInterface $token)
    {
        if ($this->canEdit($produit, $token))
            return true;

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_LOGISTICIEN']))
            return true;

        return false;
    }

    private function canAdd(ProduitInterface $produit, TokenInterface $token)
    {
        if ($this->canEdit($produit, $token))
            return true;

        return false;
    }

    private function canEdit(ProduitInterface $produit, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_LOGISTICIEN']))
            return false;

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {
            $commerce = $produit->getCommerce();

            if (!$commerce)
                return false;

            $user = $token->getUser();

            if ($commerce->getUser() == $user->getUsername())
                return true;
        }
        return false;
    }

    private function canDelete(ProduitInterface $produit, TokenInterface $token)
    {
        if ($this->canEdit($produit, $token)) {
            return true;
        }
        return false;
    }
}