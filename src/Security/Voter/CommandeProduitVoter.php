<?php

namespace App\Security\Voter;

use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
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
class CommandeProduitVoter extends Voter
{
    const ADD = 'new';
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';
    private $decisionManager;
    /**
     * @var CommandeInterface
     */
    private $commande;

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
        return $subject instanceof CommandeProduitInterface && in_array(
                $attribute,
                [self::ADD, self::SHOW, self::EDIT, self::DELETE]
            );
    }

    /**
     * @param string $attribute
     * @param CommandeProduitInterface $commandeProduit
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $commandeProduit, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ECOMMERCE_ADMIN'])) {
            return true;
        }

        $this->commande = $commandeProduit->getCommande();

        if (!$this->commande) {
            return false;
        }

        switch ($attribute) {
            case self::SHOW:
                return $this->canView($commandeProduit, $token);
            case self::ADD:
                return $this->canAdd($commandeProduit, $token);
            case self::EDIT:
                return $this->canEdit($commandeProduit, $token);
            case self::DELETE:
                return $this->canDelete($commandeProduit, $token);
        }

        return false;
    }

    private function canView(CommandeProduitInterface $commandeProduit, TokenInterface $token)
    {
        if ($this->canEdit($commandeProduit, $token)) {
            return true;
        }

        return false;
    }

    private function canAdd(CommandeProduitInterface $commandeProduit, TokenInterface $token)
    {
        if ($this->canEdit($commandeProduit, $token)) {
            return true;
        }

        return false;
    }

    private function canEdit(CommandeProduitInterface $commandeProduit, TokenInterface $token)
    {
        //on ne modifie pas une commande paye
        if ($this->commande->isPaye()) {
            return false;
        }

        $user = $token->getUser();

        if ($this->commande->getUser() == $user->getUsername()) {
            return true;
        }

        return false;
    }

    private function canDelete(CommandeProduitInterface $commandeProduit, TokenInterface $token)
    {
        if ($this->canEdit($commandeProduit, $token)) {
            return true;
        }
    }
}