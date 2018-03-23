<?php

namespace App\Tests\Admin\Access;

use App\Tests\BaseUnit;

class ValiderControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/validation/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

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

        $url = '/admin/validation/';

        $crawler = $this->executeUrl($url, $this->commerceMalice, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        $crawler = $this->executeUrl($url, $this->commerceMalice, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

    }

    public function testValidationEnka()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);

        $commande = $this->getCommande(
            [
                'valide' => false,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "commande ".$commande->getId();

        $url = '/admin/validation/';

        $crawler = $this->executeUrl($url, $this->commerceEnka, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        $crawler = $this->executeUrl($url, $this->commerceEnka, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }


}
