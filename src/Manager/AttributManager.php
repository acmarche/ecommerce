<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 12:11
 */

namespace App\Manager;

use App\Entity\Attribut\Attribut;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Manager\InterfaceDef\AttributManagerInterface;
use App\Repository\Attribut\AttributRepository;
use Doctrine\ORM\EntityManagerInterface;

class AttributManager extends AbstractManager implements AttributManagerInterface
{
    private $attributRepository;

    public function __construct(EntityManagerInterface $entityManager, AttributRepository $attributRepository)
    {
        $this->attributRepository = $attributRepository;
        parent::__construct($entityManager);
    }

    /**
     * @return Attribut
     */
    public function init(ListingAttributsInterface $listingAttributs)
    {
        $attribut = new Attribut();
        $attribut->setListingAttributs($listingAttributs);

        return $attribut;
    }

    /**
     * @param AttributInterface $attribut
     * @param string $nom
     * @param string $valeur
     * @return AttributInterface|void
     */
    public function create(AttributInterface $attribut, string $nom, string $valeur = null)
    {
        $attribut->setNom($nom);
        if ($valeur) {
            $attribut->setValeur($valeur);
        }
    }

    public function insert(AttributInterface $attributs)
    {
        $this->persist($attributs);
        $this->flush();
    }

    public function update(AttributInterface $attributs)
    {
        $this->flush();
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     * @param array $attributs
     */
    function addToCommandeProduit(CommandeProduitInterface $commandeProduit, $attributs)
    {
        foreach ($attributs as $attributId) {
            if ($attribut = $this->attributRepository->find($attributId)) {
                $commandeProduit->addAttribut($attribut);
            }
        }
    }

}