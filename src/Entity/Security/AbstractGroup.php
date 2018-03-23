<?php

namespace App\Entity\Security;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 */
abstract class AbstractGroup implements GroupInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var array
     */
    protected $roles;

    /**
     * Group constructor.
     *
     * @param string $name
     * @param array  $roles
     */
    public function __construct($name, $roles = array())
    {
        $this->name = $name;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }
}
