<?php

namespace App\Tests\Admin\Access;

use App\Tests\BaseUnit;

class CommandeLunchControllerTest extends BaseUnit
{
    public function t2estIndex()
    {
        $url = '/admin/commande/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandePortePasPaye()
    {
        $porte = $this->getCommerce(['user' => 'porte']);

        $commande = $this->getCommande([
            'paye' => 0,
            'commerce' => $porte,
            'user' => 'homer'
        ]);

        $url = '/admin/commande/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandePortePaye()
    {
        $porte = $this->getCommerce(['user' => 'porte']);

        $commande = $this->getCommande([
            'paye' => true,
            'commerce' => $porte,
            'user' => 'homer'
        ]);

        $url = '/admin/commande/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandeEnkaPasPaye()
    {
        $enka = $this->getCommerce(['user' => 'enka']);

        $commande = $this->getCommande([
            'paye' => false,
            'commerce' => $enka,
            'user' => 'zora'
        ]);

        $url = '/admin/commande/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandeEnkaPaye()
    {
        $enka = $this->getCommerce(['user' => 'enka']);

        $commande = $this->getCommande([
            'paye' => true,
            'commerce' => $enka,
            'user' => 'zora'
        ]);

        $url = '/admin/commande/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

}
