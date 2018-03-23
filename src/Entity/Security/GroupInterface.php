<?php

namespace App\Entity\Security;

/**

 */
interface GroupInterface
{
    /**
     * @param string $role
     *
     * @return self
     */
    public function addRole($role);

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role);

    /**
     * @return array
     */
    public function getRoles();

    /**
     * @param string $role
     *
     * @return self
     */
    public function removeRole($role);

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles);
}
