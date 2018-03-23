<?php

namespace App\DataFixtures\ORM;

use App\Entity\Livraison\LieuLivraison;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadLivraison extends Fixture implements ORMFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadLieuxLivraison($manager);
    }

    public function loadLieuxLivraison(ObjectManager $manager)
    {
        $lieulivaison = new LieuLivraison();
        $lieulivaison->setNom("Wex");
        $lieulivaison->setRue("Route du wex");
        $lieulivaison->setNumero(5);
        $lieulivaison->setCodePostal(6900);
        $lieulivaison->setLocalite("Marche");
        $manager->persist($lieulivaison);

        $lieulivaison = new LieuLivraison();
        $lieulivaison->setNom("Garage Hyundai");
        $lieulivaison->setRue("Rue des Deux Provinces");
        $lieulivaison->setNumero(2);
        $lieulivaison->setCodePostal(6900);
        $lieulivaison->setLocalite("Marche");

        $manager->persist($lieulivaison);
        $manager->flush();
    }

}
