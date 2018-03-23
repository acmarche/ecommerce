<?php

namespace App\Tests\Admin\Traitement;

use App\Tests\BaseUnit;

class LivraisonControllerTest extends BaseUnit
{
    public function testCommandeAccessTraiter()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1]);
        $url = '/admin/logisticien/livrer/'.$commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandeLunch()
    {
        $url = '/admin/logisticien/';
        $this->executeUrl($url, $this->logisticien, 200);

        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1]);

        $url = '/admin/commande/lunch/'.$commande->getId();

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $crawler = $this->logisticien->click($crawler->selectLink('Traiter')->link());

        $form = $crawler->selectButton('valider la livraison')->form(
            [
                'traiter_commande[livraisonRemarque]' => 'Ok fait',
            ]
        );
        $form['traiter_commande[livre]']->tick();

        $this->logisticien->submit($form);
        $crawler = $this->logisticien->followRedirect();

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("Zora")')->count()
        );
    }


    public function testCommandeClassic()
    {
        $url = '/admin/logisticien/';
        $this->executeUrl($url, $this->logisticien, 200);

        $commerce = $this->getCommerce(['user' => 'malice']);
        $commande = $this->getCommande(['user' => 'homer', 'paye' => 1, 'commerce' => $commerce]);

        $url = '/admin/commande/'.$commande->getId();

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $crawler = $this->logisticien->click($crawler->selectLink('Traiter')->link());

        $form = $crawler->selectButton('valider la livraison')->form(
            [
                'traiter_commande[livraisonRemarque]' => 'Ok classic',
            ]
        );
        $form['traiter_commande[livre]']->tick();

        $this->logisticien->submit($form);
        $crawler = $this->logisticien->followRedirect();

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("Homer")')->count()
        );
    }

}
