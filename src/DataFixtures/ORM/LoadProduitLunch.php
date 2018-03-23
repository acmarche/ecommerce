<?php

namespace App\DataFixtures\ORM;

use App\Entity\Prix\Prix;
use App\Entity\Produit\Produit;
use App\Entity\Produit\ProduitImage;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadProduitLunch extends Fixture implements ORMFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $produits = FixtureLunchConstant::PRODUIS_LUNCH_CLASSIQUES;

        $prix = [2.25, 3.10, 2.70, 2.60, 3.0, 2.45, 3.5, 3.2, 3, 2.3, 2.35];
        $categorie = $this->getReference('Sandwichs classiques');

        $this->loadDb($produits, $prix, $categorie);

        $prix = [3.25, 3.10, 3.70, 3.60, 3.0, 3.45, 3.5, 3.2];

        $categorie = $this->getReference('Sandwichs spécialisés');

        $produits = FixtureLunchConstant::PRODUIS_LUNCH_SPECIALISE;

        $this->loadDb($produits, $prix, $categorie);

        $manager->flush();
    }

    protected function loadDb($produits, $dataPrix, $categorie)
    {
        $ingredients = FixtureLunchConstant::INGREDIENTS;
        $supplements = FixtureLunchConstant::SUPLLEMENTS;

        foreach ($produits as $produitData) {

            $commerce = key($produitData);
            $nom = $produitData[$commerce];

            $clefPrix = rand(0, (count($dataPrix) - 1));
            $countIngredients = rand(0, 4);
            $countSupplements = rand(0, 4);
            $image = rand(1, 31);

            $produit = new Produit();
            $prix = new Prix();
            $prix->setHtva($dataPrix[$clefPrix]);
            $produit->setCategorie($categorie);
            $produit->setIsFood(true);
            $produit->setNom($nom);
            $produit->setPrix($prix);
            //   $produit->setImageName($image.'.jpg');
            $produit->setCommerce($this->getReference($commerce));
            /*    for ($i = 0; $i < $countIngredients; $i++) {
                    $ingredient = rand(0, (count($ingredients) - 1));
                    $produit->addIngredient($this->getReference("ingre-".$ingredients[$ingredient]));
                }
                for ($i = 0; $i < $countSupplements; $i++) {
                    $supplement = rand(0, (count($supplements) - 1));
                    $produit->addSupplement($this->getReference("supp-".$supplements[$supplement]));
                }*/
            $this->manager->persist($produit);

            if (!$this->hasReference("prod-".$nom)) {
                $this->addReference("prod-".$nom, $produit);
            }

            $produitImage = new ProduitImage($this->getReference("prod-".$nom), $image.'.jpg');
            $this->manager->persist($produitImage);
        }
    }

    public function getOrder()
    {
        return array(
            LoadCommerce::class,
            LoadCategorie::class,
        );
    }

}
