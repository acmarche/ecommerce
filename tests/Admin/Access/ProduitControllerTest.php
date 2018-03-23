<?php

namespace App\Tests\Admin\Access;

use App\Tests\BaseUnit;

class ProduitControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/produit/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
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
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->commerceMalice, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowProduitPorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->commerceMalice, 403);
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
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->commerceMalice, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditProduitPorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId().'/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->commerceMalice, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowProduitMalice()
    {
        $commerce = $this->getCommerce(['user' => 'malice']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->commerceMalice, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditProduitMalice()
    {
        $commerce = $this->getCommerce(['user' => 'malice']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId().'/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->commerceMalice, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }


}
