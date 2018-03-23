<?php

namespace App\Tests\Admin\Access;

use App\Tests\BaseUnit;

class LogisticienControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/logisticien/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowProduitEnka()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditProduitEnka()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId().'/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCantViewMalice()
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

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testCantViewPorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);

        $commande = $this->getCommande(
            [
                'valide' => false,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testCanViewMalice()
    {
        $commerce = $this->getCommerce(['user' => 'malice']);

        $commande = $this->getCommande(
            [
                'valide' => true,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }



}
