<?php

namespace App\Security\Voter;

use App\Entity\InterfaceDef\ListingAttributsInterface;
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
class ListingAttributVoter extends Voter
{
    private $decisionManager;

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
        return $subject instanceof ListingAttributsInterface && in_array($attribute, [self::SHOW, self::EDIT, self::DELETE]);
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $listingAttributs, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->canShow($listingAttributs, $token);
            case self::EDIT:
                return $this->canEdit($listingAttributs, $token);
            case self::DELETE:
                return $this->canDelete($listingAttributs, $token);
        }

        return false;
    }

    private function canShow(ListingAttributsInterface $listingAttributs, TokenInterface $token)
    {
        if ($this->canEdit($listingAttributs, $token)) {
            return true;
        }

        return false;
    }

    private function canEdit(ListingAttributsInterface $listingAttributs, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_COMMERCE'])) {

            $commerce = $listingAttributs->getCommerce();

            if (!$commerce) {
                return false;
            }

            $user = $token->getUser();
            if ($commerce->getUser() == $user->getUsername()) {
                return true;
            }

            return false;
        }

        return false;
    }

    private function canDelete(ListingAttributsInterface $listingAttributs, TokenInterface $token)
    {
        if ($this->canEdit($listingAttributs, $token)) {
            return true;
        }

        return false;
    }

}
