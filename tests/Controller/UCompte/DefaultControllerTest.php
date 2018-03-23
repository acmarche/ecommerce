<?php

namespace App\Tests\Controller\Traitement;

use App\Tests\BaseUnit;

class DefaultControllerTest extends BaseUnit
{
    public function testAnonyme()
    {
        $url = '/utilisateur/';
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testAccess()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1, 'livre' => 1]);

        $url = '/utilisateur/';
        $nom = "commande ".$commande->getId();

        $users = [
            $this->admin,
            $this->logisticien,
            $this->commercePorte,
            $this->commerceEnka,
            $this->clientHomer,
        ];

        foreach ($users as $user) {
            $crawler = $this->executeUrl($url, $user, 200);
            $this->assertEquals(
                0,
                $crawler->filter('td:contains("'.$nom.'")')->count()
            );
        }
    }

    public function testShowAccess()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1, 'livre' => 1]);

        $url = '/commande/'.$commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
    }

    public function testCommande()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1, 'livre' => 1]);
        $commandeProduits = $commande->getCommandeProduits();
        $produit = $commandeProduits[0]->getProduit();

        $url = '/utilisateur/';

        $crawler = $this->executeUrl($url, $this->clientZora, 200);
        $nom = "commande ".$commande->getId();

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        $crawler = $this->logisticien->click($crawler->selectLink($nom)->link());

        $nomProduit = $produit->getNom();

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nomProduit.'")')->count()
        );
    }

}
