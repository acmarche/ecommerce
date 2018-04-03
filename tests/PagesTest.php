<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PagesTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/produits'];
        yield ['/categories/'];
        yield ['/commerces/'];
        yield ['/contact'];
        yield ['/doc/paiement'];
        yield ['/doc/dgpr'];
        yield ['/doc/condition'];
        yield ['/doc/livraison'];
    }

}
