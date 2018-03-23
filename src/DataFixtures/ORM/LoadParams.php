<?php

namespace App\DataFixtures\ORM;

use App\Entity\Params;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadParams extends Fixture implements ORMFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $defaultTva = new Params();
        $defaultTva->setNom('default_tva');
        $defaultTva->setValeur(21);
        $manager->persist($defaultTva);

        $defaultEmail = new Params();
        $defaultEmail->setNom('email_master');
        $defaultEmail->setValeur('lunch@marche.be');
        $manager->persist($defaultEmail);

        $defaultSecret = new Params();
        $defaultSecret->setNom('stripe_secret_key');
        $defaultSecret->setValeur('sk_test_******************');
        $manager->persist($defaultSecret);

        $defaultkey = new Params();
        $defaultkey->setNom('stripe_public_key');
        $defaultkey->setValeur('pk_test_*************************');
        $manager->persist($defaultkey);

        $defaultkey = new Params();
        $defaultkey->setNom('sms_login');
        $defaultkey->setValeur('monlogin');
        $manager->persist($defaultkey);

        $defaultkey = new Params();
        $defaultkey->setNom('sms_mdp');
        $defaultkey->setValeur('monmdp');
        $manager->persist($defaultkey);

        $gr = new Params();
        $gr->setNom('poids_unite');
        $gr->setValeur('Gr');
        $manager->persist($gr);

        $litre = new Params();
        $litre->setNom('poids_unite');
        $litre->setValeur('Litre');
        //  $manager->persist($litre);

        $manager->flush();
    }
}
