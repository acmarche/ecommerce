<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PagesTest extends WebTestCase
{
    private $urls;


    public function testSomething()
    {
        $urls = [
            '/',
            '/produits/',
            '/categories/',
            '/commerces/',
            '/contact',
            '/doc/paiement',
            '/doc/dgpr',
            '/doc/condition',
            '/doc/livraison',
        ];
        $client = static::createClient();
        foreach ($urls as $url) {
            $client->request('GET', $url);
            //print_r($client->getResponse()->getContent());

            $this->assertEquals(200, $client->getResponse()->getStatusCode(), $url);
        }
    }


}
