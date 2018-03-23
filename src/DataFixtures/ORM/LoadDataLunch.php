<?php

namespace App\DataFixtures\ORM;

use App\Entity\Lunch\Ingredient;
use App\Entity\Lunch\Supplement;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;


class LoadDataLunch extends Fixture implements ORMFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $supplements = FixtureLunchConstant::SUPLLEMENTS;
        $commerces = FixtureLunchConstant::COMMERCES;
        $ingredients = FixtureLunchConstant::INGREDIENTS;

        $prix = [0.10, 0.20, 0.30, 0.50];

        foreach ($supplements as $nom) {
            $commerce = rand(0, 4);
            $clefPrix = rand(0, 2);
            $supplement = new Supplement();
            $supplement->setNom($nom);
            $supplement->setPrix($prix[$clefPrix]);
            $supplement->setCommerce($this->getReference($commerces[$commerce]));
            $manager->persist($supplement);
            $this->addReference("supp-".$nom, $supplement);
        }

        foreach ($ingredients as $nom) {
            $commerce = rand(0, 4);
            $ingredient = new Ingredient();
            $ingredient->setNom($nom);
            $ingredient->setCommerce($this->getReference($commerces[$commerce]));
            //  $ingredient->addProduit();
            $manager->persist($ingredient);
            $this->addReference("ingre-".$nom, $ingredient);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            LoadCommerce::class,
        );
    }
}
