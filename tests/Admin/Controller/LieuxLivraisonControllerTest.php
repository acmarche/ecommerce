<?php

namespace App\Tests\Admin\Controller;

use App\Tests\BaseUnit;

class LieuxLivraisonControllerTest extends BaseUnit
{
    public function testAdd()
    {
        $url = '/admin/lieu_livraison/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Ajouter')->link());

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'lieu_livraison[nom]' => 'Dpot central',
                'lieu_livraison[rue]' => 'Rue du dommerce',
                'lieu_livraison[numero]' => 12,
                'lieu_livraison[codePostal]' => 699,
                'lieu_livraison[localite]' => 'Marche',
                'lieu_livraison[description]' => 'Trucs sucré et gras',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("Dpot central")')->count()
        );
    }

    public function testEdit()
    {
        $url = '/admin/lieu_livraison/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Dpot central')->link());
        $crawler = $this->admin->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Mettre à jour')->form(
            [
                'lieu_livraison[nom]' => 'Depot central',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("Depot central")')->count()
        );
    }

}
