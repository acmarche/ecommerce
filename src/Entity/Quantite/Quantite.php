<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 14:29
 */

namespace App\Entity\Quantite;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Quantite\QuantiteRepository")
 */
class Quantite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * -1 = infinis
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $stock = -1;

    /**
     * -1 = infinis
     * @var integer|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $stock_journalier = -1;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return int
     */
    public function getStockJournalier(): ?int
    {
        return $this->stock_journalier;
    }

    /**
     * @param int $stock_journalier
     */
    public function setStockJournalier(int $stock_journalier): void
    {
        $this->stock_journalier = $stock_journalier;
    }

}