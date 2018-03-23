<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/12/17
 * Time: 14:24
 */

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class BaseImage
 * @package App\Entity\base
 * ORM\Entity() //pour generation setter/getter
 */
abstract class BaseImage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="image_name", nullable=false)
     *
     * @var string $name
     */
    protected $name;

    /**
     * Pour afficher dans template
     *
     * @var string $name
     */
    protected $url;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     *
     */
    protected $position = 0;

    /**
     * STOP
     */

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return BaseImage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return BaseImage
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

}