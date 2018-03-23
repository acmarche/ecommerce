<?php

namespace App\Tests\Controller;

use App\Tests\BaseUnit;

class DefaultControllerTest extends BaseUnit
{
    public function testIndex()
    {
        //     print_r($this->logisticien->getResponse()->getContent());
        $url = '/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 200);
    }

    public function testUrls()
    {
        $urls = [
            '/produits/',
            '/categories/',
            '/commerces/',
            '/doc/paiement',
            '/doc/dgpr',
            '/doc/condition',
            '/doc/livraison',
        ];

        foreach ($urls as $url) {
            $this->executeUrl($url, $this->admin, 200);
            $this->executeUrl($url, $this->logisticien, 200);
            $this->executeUrl($url, $this->commercePorte, 200);
            $this->executeUrl($url, $this->commerceEnka, 200);
            $this->executeUrl($url, $this->clientHomer, 200);
            $this->executeUrl($url, $this->clientZora, 200);
            $this->executeUrl($url, $this->anonyme, 200);
        }
    }

    public function testMonCompte()
    {
        $url = '/utilisateur/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCategorie()
    {
        $urls = [];

        $categorie = $this->getCategorie(['nom' => 'Lunch']);
        $urls [] = '/categories/'.$categorie->getId();

        $categorie = $this->getCategorie(['nom' => 'Sandwichs classiques']);
        $urls [] = '/categories/'.$categorie->getId();

        foreach ($urls as $url) {
            $this->executeUrl($url, $this->admin, 200);
            $this->executeUrl($url, $this->logisticien, 200);
            $this->executeUrl($url, $this->commercePorte, 200);
            $this->executeUrl($url, $this->commerceEnka, 200);
            $this->executeUrl($url, $this->clientHomer, 200);
            $this->executeUrl($url, $this->clientZora, 200);
            $this->executeUrl($url, $this->anonyme, 200);
        }

    }

    public function testProduit()
    {
        $produits = $this->getProduits(['nom' => 'Boulette']);
        foreach ($produits as $produit) {

            $url = '/produits/'.$produit->getId();

            $this->executeUrl($url, $this->admin, 200);
            $this->executeUrl($url, $this->logisticien, 200);
            $this->executeUrl($url, $this->commercePorte, 200);
            $this->executeUrl($url, $this->commerceEnka, 200);
            $this->executeUrl($url, $this->clientHomer, 200);
            $this->executeUrl($url, $this->clientZora, 200);
            $this->executeUrl($url, $this->anonyme, 200);
        }
    }

}
