<?php

namespace App\Tests\Unitaire;

use App\Entity\Prix\Prix;
use App\Entity\Produit\Produit;
use App\Manager\PanierManager;
use App\Service\QuantiteService;
use App\Tests\BaseUnit;

class PanierControllerTest extends BaseUnit
{
    private $produit;
    private $commerce;
    private $url;

    public function __construct()
    {
        parent::__construct();

        $commerce = $this->getCommerce(['user' => 'enka']);
        $produits = $commerce->getProduits();
        $produit = $produits[0];

        $url = '/produits/'.$produit->getId();

        $this->produit = $produit;
        $this->commerce = $commerce;
        $this->url = $url;
    }


    public function testAdd()
    {
        $panierUtil = $this->createMock(PanierManager::class);
        $quantiteService = $this->createMock(QuantiteService::class);

        $quantiteService->expects($this->once())->method('checkQuantite');

        $quantite = 0;
        $commercePorte = $this->getCommerce(['user' => 'porte']);
        $commerceEnka = $this->getCommerce(['user' => 'enka']);
        $user = $this->getUser(['username' => 'zora']);

        $produits = $this->getProduits(['commerce' => $commercePorte]);
        $produitPorte = $produits[0];

        $produits = $this->getProduits(['commerce' => $commercePorte]);
        $produitEnka = $produits[0];

        $panierUtil->expects($this->any())
            ->method('checkCommerce')
            ->willReturn($commercePorte);

        $this->assertTrue($quantiteService->checkQuantite(5));

        /*  try {

          } catch (\Exception $exception) {
              var_dump($exception);
              $this->assertEquals(
                  'Quantité minimum = 1',
                  $exception->getMessage()
              );
          }


          $panierUtil->checkProduitIsOwnedCommerce($produitEnka, $commercePorte);

          $panierUtil->commandeExistPanier($commercePorte, $produitPorte, $user);
          $commandeProduit = $panierUtil->produitExistPanier($produitPorte, $commercePorte, $user);


          try {
              $panierUtil->addProduit($produitPorte, $commercePorte, $user, $quantite);
          } catch (\Exception $exception) {
              var_dump($exception->getMessage());
          }

          dump($commandeProduit->getQuantite());*/

    }

    public function createProduitNoDb()
    {
        $produit = new Produit();
        $produit->setNom("Faux");
        $prix = new Prix();
        $prix->setHtva(5);
        $produit->setPrix($prix);

        return $produit;

    }
}
