<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/03/18
 * Time: 10:32
 */

namespace App\Entity\Security;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * https://symfony.com/doc/current/doctrine/registration_form.html#having-a-registration-form-with-only-email-no-username
 * @ORM\Entity(repositoryClass="App\Repository\Security\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Table(name="user")
 */
class User extends AbstractUser implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string|null
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=2)
     * @var string|null
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null
     */
    protected $mobile;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @var boolean
     */
    protected $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="user_group")
     *
     */
    protected $groups;

    public function __construct()
    {
        $this->isActive = true;
        $this->groups = [];
        parent::__construct();
    }

    public function getSalt()
    {

    }

    /**
     * @return boolean
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return null|string
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @param null|string $mobile
     */
    public function setMobile(?string $mobile): void
    {
        $this->mobile = $mobile;
    }

}