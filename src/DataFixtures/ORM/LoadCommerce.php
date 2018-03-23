<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Commerce\Commerce;

class LoadCommerce extends Fixture implements ORMFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $porte = new Commerce();
        $porte->setBottinId(178);
        $porte->setCreatedAt(new \DateTime());
        $porte->setUpdatedAt(new \DateTime());
        $porte->setNom("Bonne porte");
        $porte->setUser("porte");
        $porte->setNumeroTva("BE 02489.140.983");
        $porte->setIban("BE87 1030 3882 2094");
        $porte->setImageName("porte.jpg");
        $manager->persist($porte);
        $this->addReference("porte", $porte);

        $grain = new Commerce();
        $grain->setBottinId(69);
        $grain->setCreatedAt(new \DateTime());
        $grain->setUpdatedAt(new \DateTime());
        $grain->setNom("9 GRAINS D'OR");
        $grain->setNumeroTva("BE 02489.140.983");
        $grain->setIban("BE87 1030 3882 2094");
        $grain->setImageName("grain.jpg");
        $manager->persist($grain);
        $this->addReference("grain", $grain);

        $leonidas = new Commerce();
        $leonidas->setBottinId(1294);
        $leonidas->setCreatedAt(new \DateTime());
        $leonidas->setUpdatedAt(new \DateTime());
        $leonidas->setNom("LEONIDAS");
        $leonidas->setUser("leonidas");
        $leonidas->setNumeroTva("BE 02489.140.983");
        $leonidas->setIban("BE87 1030 3882 2094");
        $leonidas->setImageName("leonidas.jpg");
        $manager->persist($leonidas);
        $this->addReference("leonidas", $leonidas);

        $enka = new Commerce();
        $enka->setBottinId(1442);
        $enka->setCreatedAt(new \DateTime());
        $enka->setUpdatedAt(new \DateTime());
        $enka->setNom("L'ENKA TOQUE");
        $enka->setNumeroTva("BE 02489.140.983");
        $enka->setIban("BE87 1030 3882 2094");
        $enka->setImageName("enka.jpg");
        $manager->persist($enka);
        $this->addReference("enka", $enka);

        $boite = new Commerce();
        $boite->setBottinId(413);
        $boite->setCreatedAt(new \DateTime());
        $boite->setUpdatedAt(new \DateTime());
        $boite->setNom("LA BOITE A MALICES");
        $boite->setUser("boite");
        $boite->setNumeroTva("BE 02489.140.983");
        $boite->setIban("BE87 1030 3882 2094");
        $boite->setImageName("boite.jpg");
        $manager->persist($boite);
        $this->addReference("boite", $boite);

        $pause = new Commerce();
        $pause->setBottinId(1278);
        $pause->setCreatedAt(new \DateTime());
        $pause->setUpdatedAt(new \DateTime());
        $pause->setNom("La pause Chocolat Thé");
        $pause->setNumeroTva("BE 02489.140.983");
        $pause->setIban("BE87 1030 3882 2094");
        $pause->setImageName("pause.jpg");
        $manager->persist($pause);
        $this->addReference("pause", $pause);

        $vin = new Commerce();
        $vin->setBottinId(993);
        $vin->setCreatedAt(new \DateTime());
        $vin->setUpdatedAt(new \DateTime());
        $vin->setNom("LA CAVE DES SOMMELIERS");
        $vin->setUser("vin");
        $vin->setNumeroTva("BE 02489.140.983");
        $vin->setIban("BE87 1030 3882 2094");
        $vin->setImageName("sommelier.jpg");
        $manager->persist($vin);
        $this->addReference("vin", $vin);

        $maya = new Commerce();
        $maya->setBottinId(1500);
        $maya->setCreatedAt(new \DateTime());
        $maya->setUpdatedAt(new \DateTime());
        $maya->setNom("mayaCocoa");
        $maya->setNumeroTva("BE 02489.140.983");
        $maya->setIban("BE87 1030 3882 2094");
        $maya->setImageName("maya.jpg");
        $manager->persist($maya);
        $this->addReference("maya", $maya);

        $iwago = new Commerce();
        $iwago->setBottinId(896);
        $iwago->setCreatedAt(new \DateTime());
        $iwago->setUpdatedAt(new \DateTime());
        $iwago->setNom("IWAGO SNACK");
        $iwago->setUser("iwago");
        $iwago->setNumeroTva("BE 02489.140.983");
        $iwago->setIban("BE87 1030 3882 2094");
        $iwago->setImageName("iwago.png");
        $manager->persist($iwago);
        $this->addReference("iwago", $iwago);

        $lefevre = new Commerce();
        $lefevre->setBottinId(356);
        $lefevre->setCreatedAt(new \DateTime());
        $lefevre->setUpdatedAt(new \DateTime());
        $lefevre->setNom("LEFEVRE");
        $lefevre->setUser("lefevre");
        $lefevre->setNumeroTva("BE 02489.140.983");
        $lefevre->setIban("BE87 1030 3882 2094");
        $lefevre->setImageName("lefevre.jpg");
        $manager->persist($lefevre);
        $this->addReference("lefevre", $lefevre);

        $manager->flush();
    }
}
