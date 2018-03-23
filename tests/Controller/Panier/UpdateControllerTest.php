<?php

namespace App\Tests\Controller\Panier;

use App\Entity\Commande\CommandeProduit;
use App\Tests\BaseUnit;
use Symfony\Bundle\FrameworkBundle\Client;

class UpdateControllerTest extends BaseUnit
{
    private $commandeProduit;
    private $produit;
    private $urlUpdate;
    private $urlPanier;
    private $commande;

    public function __construct()
    {
        parent::__construct();

        $commande = $this->getCommande(['user' => 'zora', 'paye' => 0]);
        $commandeProduits = $commande->getCommandeProduits();
        $commandeProduit = $commandeProduits[0];
        $produit = $commandeProduit->getProduit();

        $this->urlUpdate = '/panier/update/'.$commandeProduit->getId();
        $this->urlPanier = '/panier/';

        $this->commande = $commande;
        $this->produit = $produit;
        $this->commandeProduit = $commandeProduit;
    }

    public function te2stIndex()
    {
        $this->executeUrl($this->urlPanier, $this->admin, 200);
        $this->executeUrl($this->urlPanier, $this->logisticien, 200);
        $this->executeUrl($this->urlPanier, $this->commercePorte, 200);
        $this->executeUrl($this->urlPanier, $this->commerceEnka, 200);
        $this->executeUrl($this->urlPanier, $this->commerceEnka, 200);
        $this->executeUrl($this->urlPanier, $this->clientHomer, 200);
        $this->executeUrl($this->urlPanier, $this->clientZora, 200);
        $this->executeUrl($this->urlPanier, $this->anonyme, 302);
    }

    public function te2stContenu()
    {
        $users = [
            $this->admin,
            $this->logisticien,
            $this->commercePorte,
            $this->commerceEnka,
            $this->clientHomer,
        ];

        $nom = $this->produit->getNom();

        foreach ($users as $user) {
            $crawler = $this->executeUrl($this->urlPanier, $user, 200);

            $this->assertEquals(
                0,
                $crawler->filter('td:contains("'.$nom.'")')->count()
            );
        }

        $crawler = $this->executeUrl($this->urlPanier, $this->clientZora, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testUpdateBadToken()
    {
        $response = $this->updatePanier($this->clientZora, 403, 1, "jksdjkqsdjlqd");

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "Token invalide");
    }

    public function testUpdateBadQuantite()
    {
        $response = $this->updatePanier($this->clientZora, 200, 0);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "Quantité minimum = 1");
    }

    /**
     * token pas valide d'office
     */
    public function te2stUpdateCantAccess()
    {
        $args = ['user' => 'homer', 'paye' => false];
        $commande = $this->getCommande($args);
        $commandeProduits = $commande->getCommandeProduits();
        $commandeProduit = $commandeProduits[0];

        $response = $this->updatePanier($this->clientZora, 403, 4, null, $commandeProduit);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        //token invalid car base sur l'objet
        $this->assertEquals($JSON_response["data"]["error"], "Token invalide");
    }

    /**
     * token pas valide d'office
     */
    public function te2stUpdateCommandeCloture()
    {
        $args = ['user' => 'zora', 'paye' => true];
        $commande = $this->getCommande($args);
        $commandeProduits = $commande->getCommandeProduits();
        $commandeProduit = $commandeProduits[0];

        $response = $this->updatePanier($this->clientZora, 200, null, null, $commandeProduit);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "commande déjà payée");
    }

    public function testUpdateok()
    {
        $response = $this->updatePanier($this->clientZora, 200, 5);

        $JSON_response = json_decode($response, true);

        $this->assertNotEmpty($JSON_response);
        $data = $JSON_response["data"];

        $this->assertArrayHasKey("produit", $data);
        $this->assertArrayHasKey("commande", $data);
    }

    public function updatePanier(
        Client $client,
        $codeAttendu,
        $quantite = 1,
        $token = null,
        CommandeProduit $commandeProduit = null
    ) {

        if (!$commandeProduit) {
            $commandeProduit = $this->commandeProduit;
        }

        $urlUpdate = '/panier/update/'.$commandeProduit->getId();
        $crawler = $this->executeUrl($this->urlPanier, $client, 200);

      //  print_r($client->getResponse()->getContent());

        $form = $crawler->selectButton('Confirmer')->form(
            [

            ]
        );

        if (!$token) {
            $token = $form->get('tokenPanier-'.$commandeProduit->getId())->getValue();
        }

        $data = [
            "quantiteProduit" => $quantite,
            "token" => $token,
        ];

        $crawler = $client->request(
            'POST',
            $urlUpdate,
            $data
        );


        $this->assertEquals($codeAttendu, $client->getResponse()->getStatusCode());

        return $client->getResponse()->getContent();
    }

    public function createCommandeProduitNoDb()
    {
        $commandeProduit = new CommandeProduit();

        return $commandeProduit;
    }
}
