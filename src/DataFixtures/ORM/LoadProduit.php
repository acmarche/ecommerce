<?php

namespace App\DataFixtures\ORM;

use App\Entity\Categorie\Categorie;
use App\Entity\Commerce\Commerce;
use App\Entity\Prix\Prix;
use App\Entity\Produit\Produit;
use App\Entity\Produit\ProduitImage;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadProduit extends Fixture implements ORMFixtureInterface
{
    private $manager;
    private $commerce;
    private $categorie;

    public function load(ObjectManager $manager)
    {
        $fichier = __DIR__.'/all.csv';
        $this->commerce = null;
        $this->categorie = null;

        if (file_exists($fichier)) {
            $handle = fopen($fichier, "r");
            while (($row = fgetcsv($handle, 0, "|")) !== false) {

                $nom = $row[0];
                //colonne titre
                if ($nom == 'nom') {
                    continue;
                }

                //nouveau commerce
                if (preg_match("|\#|", $nom)) {
                    $nom = preg_replace("|\#|", "", $nom);
                    switch ($nom) {
                        case 'grain':
                            $this->commerce = $manager->getRepository(Commerce::class)->findOneBy(
                                ['nom' => "9 GRAINS D'OR"]
                            );
                            $this->categorie = $manager->getRepository(Categorie::class)->findOneBy(
                                ['nom' => "Frais, bio"]
                            );
                            break;
                        case 'enka':
                            $this->commerce = $manager->getRepository(Commerce::class)->findOneBy(
                                ['nom' => "L'ENKA TOQUE"]
                            );
                            $this->categorie = $manager->getRepository(Categorie::class)->findOneBy(
                                ['nom' => "Plats chauds"]
                            );
                            break;
                        case 'leonidas':
                            $this->commerce = $manager->getRepository(Commerce::class)->findOneBy(
                                ['nom' => "LEONIDAS"]
                            );
                            $this->categorie = $manager->getRepository(Categorie::class)->findOneBy(
                                ['nom' => "Chocolateries"]
                            );
                            break;
                        case 'malice':
                            $this->commerce = $manager->getRepository(Commerce::class)->findOneBy(
                                ['nom' => "LA BOITE A MALICES"]
                            );
                            $this->categorie = $manager->getRepository(Categorie::class)->findOneBy(
                                ['nom' => "Jeux"]
                            );
                            break;
                        default:
                            $this->commerce = null;
                            $this->categorie = null;
                            break;
                    }
                    //on a enregistre le commerce
                    continue;
                }

                if ($this->commerce) {
                    $produit = new Produit();
                    $prix = new Prix();
                    $prix->setHtva(2);
                    $produit->setCategorie($this->categorie);
                    $produit->setNom($row[0]);
                    $produit->setCommerce($this->commerce);
                    $produit->setPrix($prix);
                    $manager->persist($produit);
                    $manager->flush();
                    $this->loadImage($produit, $manager);
                }
            }
        }
    }

    private function loadImage(Produit $produit, ObjectManager $objectManager)
    {
        $image = rand(1, 31);
        $produitImage = new ProduitImage($produit, $image.'.jpg');
        $objectManager->persist($produitImage);
        $objectManager->flush();
    }

    public function getOrder()
    {
        return array(
            LoadCommerce::class,
            LoadCategorie::class,
        );
    }

}
