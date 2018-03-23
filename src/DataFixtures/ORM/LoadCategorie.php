<?php

namespace App\DataFixtures\ORM;

use App\Entity\Categorie\Categorie;
use App\Entity\Categorie\CategorieImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategorie extends Fixture implements ORMFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadRoots($manager);
        $this->loadCats(FixtureConstant::CATEGORIES, 'ecommerce', $manager);
        $this->loadCats(FixtureLunchConstant::CATEGORIES, 'lunch', $manager);
    }

    public function loadRoots(ObjectManager $manager)
    {
        $lunch = new Categorie();
        $lunch->setNom("Lunch");
        $lunch->setDescription(
            'Commandez avant 11h votre diner et fait le vous livrez à votre lieu de travail.'
        );
        $lunch->setCreatedAt(new \DateTime());
        $lunch->setUpdatedAt(new \DateTime());
        $manager->persist($lunch);
        $this->addReference("lunch", $lunch);

        $categorieImage = new CategorieImage($this->getReference('lunch'), 'lunch.jpg');
        $manager->persist($categorieImage);

        $ecommerce = new Categorie();
        $ecommerce->setNom("Ecommerce");
        $ecommerce->setDescription(
            'Achetez dans les commerces près de chez vous.'
        );
        $ecommerce->setCreatedAt(new \DateTime());
        $ecommerce->setUpdatedAt(new \DateTime());
        $manager->persist($ecommerce);
        $this->addReference("ecommerce", $ecommerce);

        $categorieImage = new CategorieImage($this->getReference('ecommerce'), 'ecommerce.jpg');
        $manager->persist($categorieImage);

        $manager->flush();
    }

    public function loadCats($categories, $parent, ObjectManager $manager)
    {
        foreach ($categories as $image => $nom) {
            $categorie = new Categorie();
            $categorie->setNom($nom);
            $categorie->setParent($this->getReference($parent));
            $categorie->setDescription(
                'Sea nut perspicacity under omni piste natures mirror of
                    there with consequent.'
            );
            $categorie->setCreatedAt(new \DateTime());
            $categorie->setUpdatedAt(new \DateTime());
            $manager->persist($categorie);
            $this->addReference($nom, $categorie);

            $categorieImage = new CategorieImage($categorie, $image);
            $manager->persist($categorieImage);
        }

        $manager->flush();
    }


}
