<?php

namespace App\Tests\Admin\Traitement;

use App\Tests\BaseUnit;

class ValiderControllerTest extends BaseUnit
{
    public function testValidationmalice()
    {
        $commerce = $this->getCommerce(['user' => 'malice']);

        $commande = $this->getCommande(
            [
                'valide' => false,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "commande ".$commande->getId();

        /**
         * VISIBLE SUR LA PAGE DE BOARD
         */
        $url = '/admin/';

        $crawler = $this->executeUrl($url, $this->commerceMalice, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        /**
         * ON VALIDE
         */
        $url = '/admin/validation/';

        $crawler = $this->executeUrl($url, $this->commerceMalice, 200);

        $crawler = $this->admin->click($crawler->selectLink($nom)->link());
        $crawler = $this->admin->click($crawler->selectLink('valider la commande')->link());
        $form = $crawler->selectButton('valider la commande')->form(
            [
                'valider_commande[valideRemarque]' => 'Ok prêt',
                'valider_commande[valide]' => 1,
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("Ok prêt")')->count()
        );

        /**
         * ON NE LE VOIT PLUS SUR LE BOARD
         */
        $url = '/admin/validation/';
        $crawler = $this->executeUrl($url, $this->commerceMalice, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        /**
         * LE LOGISTICIEN VOIT LA COMMANDE PRETE A LIVRER
         */
        $nom = "commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

}
