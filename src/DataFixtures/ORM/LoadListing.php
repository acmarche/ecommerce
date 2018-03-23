<?php

namespace App\DataFixtures\ORM;

use App\Entity\Attribut\ListingAttributs;
use App\Entity\Categorie\Categorie;
use App\Entity\Categorie\CategorieImage;
use App\Entity\Commerce\Commerce;
use App\Manager\InterfaceDef\AttributManagerInterface;
use App\Manager\InterfaceDef\ListingAttributManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadListing extends Fixture implements ORMFixtureInterface
{
    private $listingAttributManager;
    private $attributManager;

    public function __construct(
        ListingAttributManagerInterface $listingAttributManager,
        AttributManagerInterface $attributManager
    ) {
        $this->listingAttributManager = $listingAttributManager;
        $this->attributManager = $attributManager;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $commerce = $manager->getRepository(Commerce::class)->findOneBy(
            ['nom' => "9 GRAINS D'OR"]
        );

        $this->create($commerce, "Accompagnements", ['Frites', 'Patates', 'Gratin dauphinois']);
        $this->create($commerce, "Tailles T-shirt", ['Xl', 'M', 'S']);
        $this->create($commerce, "Coupe du pain", ['Coupé', 'Non coupé']);
        $this->create($commerce, "Sauces", FixtureLunchConstant::SAUCES);
        $this->create($commerce, "Personnaliser", FixtureLunchConstant::SUPLLEMENTS);
    }

    public function create(Commerce $commerce, $nom, $attributs)
    {
        $listing = $this->listingAttributManager->create($commerce);
        $listing->setNom($nom);
        $this->listingAttributManager->insert($listing);

        $i = 0;
        foreach ($attributs as $nomAttribut) {
            $attribut = $this->attributManager->init($listing);
            $this->attributManager->create($attribut, $nomAttribut);
            $this->attributManager->insert($attribut);
            $i++;
            if ($i == 6) {
                break;
            }
        }
    }

    public function getOrder()
    {
        return array(
            LoadCommerce::class,
        );
    }

}
