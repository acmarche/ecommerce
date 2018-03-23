<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/09/17
 * Time: 18:48
 */

namespace App\Entity\InterfaceDef;

interface LieuLivraisonInterface
{

    public function __toString();

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return LieuLivraisonInterface
     */
    public function setNom($nom);

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom();

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return LieuLivraisonInterface
     */
    public function setRue($rue);

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue();

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return LieuLivraisonInterface
     */
    public function setNumero($numero);

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero();

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return LieuLivraisonInterface
     */
    public function setCodePostal($codePostal);

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal();

    /**
     * Set localite
     *
     * @param string $localite
     *
     * @return LieuLivraisonInterface
     */
    public function setLocalite($localite);

    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite();

    /**
     * Set description
     *
     * @param string $description
     *
     * @return LieuLivraisonInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

}