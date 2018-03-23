<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/09/17
 * Time: 19:22
 */

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseEntity
 * @package App\Entity
 * ORM\Entity()//pour generation setter/getter
 */
abstract class BaseEntity
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    protected $nom;

    public function __toString()
    {
        return $this->getNom();
    }

    /**
     * STOP
     */

    /**
     * en dessous ide genere
     */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
}