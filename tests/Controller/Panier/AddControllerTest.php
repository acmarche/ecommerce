<?php

namespace App\Tests\Controller\Panier;

use App\Entity\Commerce\Commerce;
use App\Entity\Prix\Prix;
use App\Entity\Produit\Produit;
use App\Tests\BaseUnit;
use Symfony\Bundle\FrameworkBundle\Client;

class AddControllerTest extends BaseUnit
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

    public function testIndex()
    {
        $this->executeUrl($this->url, $this->admin, 200);
        $this->executeUrl($this->url, $this->logisticien, 200);
        $this->executeUrl($this->url, $this->commercePorte, 200);
        $this->executeUrl($this->url, $this->commerceMalice, 200);
        $this->executeUrl($this->url, $this->commerceEnka, 200);
        $this->executeUrl($this->url, $this->clientHomer, 200);
        $this->executeUrl($this->url, $this->clientZora, 200);
        $this->executeUrl($this->url, $this->anonyme, 200);
    }

    public function testAddBadToken()
    {
        $response = $this->addPanier($this->clientZora, 403, 1, "jksdjkqsdjlqd");

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "Token invalide");
    }

    public function testAddBadQuantite()
    {
        $response = $this->addPanier($this->clientZora, 200, 0);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "Quantité minimum = 1");
    }

    public function testAddProduitSansStock()
    {
        $produits = $this->getProduits(['nom' => 'Sans stock']);
        $produit = $produits[0];

        $url = '/produits/'.$produit->getId();

        $response = $this->addPanier($this->clientZora, 200, 2, null, $produit, null, $url);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "Plus de stock disponible");
    }

    public function testAddProduitNotInCommerce()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);

        $response = $this->addPanier($this->clientZora, 200, 1, null, null, $commerce);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "Le produit n'appartient pas à ce commerce");
    }

    public function tstAddProduitInexistant()
    {
        /**
         * impossible a simuler a cause du token
         */
    }

    /**
     * field indisponible = true
     * Return 404 response
     */
    public function testAddProduitIndisponible()
    {
        $produits = $this->getProduits(['nom' => 'Indisponible']);
        $produit = $produits[0];

        $url = '/produits/'.$produit->getId();

        $this->addPanier($this->clientZora, 404, 1, null, $produit, null, $url);
    }

    public function testAddCommerceNotFound()
    {
        $crawler = $this->executeUrl($this->url, $this->clientZora, 200);

        $form = $crawler->selectButton('Ajouter au panier')->form(
            [
                'quantite' => 2,
                'commerce' => 9999999999,
            ]
        );

        $token = $form->get('token')->getValue();

        $data = [
            "commerce" => 9999999999,
            "quantiteProduit" => 2,
            "token" => $token,
        ];

        $crawler = $this->clientZora->request(
            'POST',
            '/panier/add/'.$this->produit->getId(),
            $data
        );

        $this->assertEquals(200, $this->clientZora->getResponse()->getStatusCode());

        $response = $this->clientZora->getResponse()->getContent();

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"]["error"], "dommerce inconnu");
    }

    public function testAddok()
    {
        $response = $this->addPanier($this->clientZora, 200);

        $JSON_response = json_decode($response, true);
        $this->assertNotEmpty($JSON_response);
        $this->assertEquals($JSON_response["data"], "1 ajouté");
    }

    public function addPanier(
        Client $client,
        $codeAttendu,
        $quantite = 1,
        $token = null,
        Produit $produit = null,
        Commerce $commerce = null,
        $url = null
    ) {

        if (!$produit) {
            $produit = $this->produit;
        }

        if (!$commerce) {
            $commerce = $this->commerce;
        }

        if (!$url) {
            $url = $this->url;
        }

        if ($codeAttendu == 404) {
            return $this->executeUrl($url, $client, $codeAttendu);
        }

        $crawler = $this->executeUrl($url, $client, 200);

        $form = $crawler->selectButton('Ajouter au panier')->form(
            [
                'quantite' => $quantite,
                'commerce' => $commerce->getId(),
            ]
        );

        if (!$token) {
            $token = $form->get('token')->getValue();
        }

        $data = [
            "commerce" => $commerce->getId(),
            "quantiteProduit" => $quantite,
            "token" => $token,
        ];

        $crawler = $client->request(
            'POST',
            '/panier/add/'.$produit->getId(),
            $data
        );

        // var_dump($client->getResponse()->getContent());

        $this->assertEquals($codeAttendu, $client->getResponse()->getStatusCode());

        return $client->getResponse()->getContent();
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
