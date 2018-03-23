<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace App\Entity\InterfaceDef;


interface CommerceInterface
{
    public function __toString();

     /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getNom();

    /**
     * @param string $nom
     */
    public function setNom($nom);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt);

    /**
     * Set tvaApplicable
     *
     * @param string $tvaApplicable
     *
     * @return CommerceInterface
     */
    public function setTvaApplicable($tvaApplicable);

    /**
     * Get tvaApplicable
     *
     * @return string
     */
    public function getTvaApplicable();

   /**
     * Set smsNumero
     *
     * @param string $smsNumero
     *
     * @return CommerceInterface
     */
    public function setSmsNumero($smsNumero);

    /**
     * Get smsNumero
     *
     * @return string
     */
    public function getSmsNumero();


    /**
     * Set bottinId
     *
     * @param integer $bottinId
     *
     * @return CommerceInterface
     */
    public function setBottinId($bottinId);

    /**
     * Get bottinId
     *
     * @return integer
     */
    public function getBottinId();

    /**
     * Set user
     *
     * @param string $user
     *
     * @return CommerceInterface
     */
    public function setUser($user);

    /**
     * Get user
     *
     * @return string
     */
    public function getUser();

}