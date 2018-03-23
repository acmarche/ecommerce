<?php

namespace App\Tests\Controller;

use App\Tests\BaseUnit;

class ContactControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);
        $url = '/commerces/'.$commerce->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 200);
    }

    public function testForm()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);
        $url = '/commerces/'.$commerce->getId();
        $crawler = $this->executeUrl($url, $this->anonyme, 200);

        $form = $crawler->selectButton('Envoyer le message')->form(
            [
                'contact_commerce[nom]' => 'Jf',
                'contact_commerce[email]' => 'jf@marche.be',
                'contact_commerce[commentaire]' => 'Voici mon message que je desire passer',
            ]
        );

        $this->anonyme->submit($form);
        $crawler = $this->anonyme->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("FRITERIE BABE")')->count()
        );
    }

}
