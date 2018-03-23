<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/18
 * Time: 17:13
 */

namespace App\Manager;

use App\Entity\Security\User;
use App\Repository\Security\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager extends AbstractManager
{
    private $userPasswordEncoder;
    private $groupRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder,
        GroupRepository $groupRepository
    ) {
        parent::__construct($entityManager);
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->groupRepository = $groupRepository;
    }

    public function newInstance()
    {
        return new User();
    }

    public function insert(User $user)
    {
        $this->setPassword($user, $user->getPlainPassword());
        $this->setRole($user);
        $this->persist($user);
        $this->flush();
    }

    public function setPassword(User $user, $plainPassword)
    {
        $password = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($password);
    }

    public function setRole(User $user)
    {
        $groupClient = $this->groupRepository->findOneBy(['name' => 'ECOMMERCE_CLIENT']);

        if (!$user->hasGroup($groupClient)) {
            $user->addGroup($groupClient);
        }
    }
}