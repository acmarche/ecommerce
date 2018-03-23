<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 2/09/17
 * Time: 17:17
 */

namespace App\Tests;

use App\Entity\Categorie\Categorie;
use App\Entity\Commande\Commande;
use App\Entity\Commande\CommandeProduit;
use App\Entity\Commerce\Commerce;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\Produit\Produit;
use App\Entity\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class BaseUnit extends WebTestCase
{
    protected $admin;
    protected $logisticien;
    protected $commercePorte;
    protected $commerceMalice;
    protected $commerceEnka;
    protected $clientHomer;
    protected $clientZora;
    protected $anonyme;
    protected $container;
    protected $em;

    public function __construct()
    {
        $this->admin = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'admin@marche.be',
                'PHP_AUTH_PW' => 'admin',
            ]
        );

        $this->logisticien = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'logisticien@marche.be',
                'PHP_AUTH_PW' => 'logisticien',
            ]
        );

        $this->commercePorte = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'porte@marche.be',
                'PHP_AUTH_PW' => 'porte',
            ]
        );

        $this->commerceEnka = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'enka@marche.be',
                'PHP_AUTH_PW' => 'enka',
            ]
        );

        $this->commerceMalice = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'malice',
                'PHP_AUTH_PW' => 'malice',
            ]
        );


        $this->clientHomer = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'homer@marche.be',
                'PHP_AUTH_PW' => 'homer',
            ]
        );

        $this->clientZora = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'zora@marche.be',
                'PHP_AUTH_PW' => 'zora',
            ]
        );

        $this->anonyme = static::createClient();

        $this->container = $this->anonyme->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();

        parent::__construct();
    }

    /**
     * @param $url
     * @param Client $user
     * @param $codeAttendu
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    public function executeUrl($url, Client $user, $codeAttendu)
    {
        $crawler = $user->request('GET', $url);
        $code = $user->getResponse()->getStatusCode();

        if ($code == 404) {
            // var_dump($url);
        }
        if ($code == 302) {
           // print_r($user->getResponse()->getContent());
        }
        $this->assertEquals($codeAttendu, $code);

        return $crawler;
    }

    /**
     * @param $object
     * @return \Symfony\Component\Security\Csrf\CsrfToken
     */
    public function generateToken($object)
    {
        $csrf = $this->container->get('security.csrf.token_manager');

        return $csrf->refreshToken($object);
    }

    /**
     * @param $args
     * @return bool|null|Commerce
     */
    protected function getCommerce($args)
    {
        $commerce = $this->em->getRepository(Commerce::class)->findOneBy(
            $args
        );

        if (!$commerce) {
            $this->assertEquals(0, 'commerce non trouve');

            return false;
        }

        return $commerce;
    }

    /**
     * @param $args
     * @return bool|CommandeInterface
     */
    protected function getCommande($args)
    {
        $commande = $this->em->getRepository(Commande::class)->findOneBy(
            $args
        );

        if (!$commande) {
            $this->assertEquals(0, 'commande non trouvee');

            return false;
        }

        return $commande;
    }

    /**
     * @param $args
     * @return bool|null|Produit[]
     */
    protected function getProduits($args)
    {
        $produits = $this->em->getRepository(Produit::class)->findBy(
            $args
        );

        if (count($produits) < 1) {
            $this->assertEquals(0, 'produits non trouve');

            return false;
        }

        return $produits;
    }

    /**
     * @param $args
     * @return bool|null|Categorie
     */
    protected function getCategorie($args)
    {
        $categorie = $this->em->getRepository(Categorie::class)->findOneBy(
            $args
        );

        if (!$categorie) {
            $this->assertEquals(0, 'categorie non trouve');

            return false;
        }

        return $categorie;
    }

    /**
     * @param $args
     * @return bool|CommandeProduitInterface
     */
    protected function getCommandeProduit($args)
    {
        $commandeProduit = $this->em->getRepository(CommandeProduit::class)->findOneBy(
            $args
        );

        if (!$commandeProduit) {
            $this->assertEquals(0, 'commandeProduit non trouve');

            return false;
        }

        return $commandeProduit;
    }

    /**
     * @param $args
     * @return bool|null|User
     */
    protected function getUser($args)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(
            $args
        );

        if (!$user) {
            $this->assertEquals(0, 'user non trouve');

            return false;
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

}