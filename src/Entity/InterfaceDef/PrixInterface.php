<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 15:24
 */

namespace App\Entity\InterfaceDef;


interface PrixInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return float
     */
    public function getHtva(): ?float;

    /**
     * @param float $htva
     */
    public function setHtva(float $htva): void;

    /**
     * @return float
     */
    public function getPromoHtva(): ?float;

    /**
     * @param float $htva_promo
     */
    public function setPromoHtva(float $htva_promo): void;

    /**
     * @return float
     */
    public function getPrixTvac(): ?float;

    /**
     * @param float $prix_tvac
     */
    public function setPrixTvac(float $prix_tvac): void;



}