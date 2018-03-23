<?php

namespace App\Tests\Controller;

use App\Tests\BaseUnit;

class SearchControllerTest extends BaseUnit
{

    public function testSearch()
    {
        $url = '/search/';
        $crawler = $this->executeUrl($url, $this->anonyme, 200);

        $form = $crawler->selectButton('Rechercher')->form(
            [
                'search_advanced[motclef]' => 'anglais',
            ]
        );

        $option = $crawler->filter('#search_advanced_commerce option:contains("Bonne porte")');
        $this->assertEquals(1, count($option), 'Bonne porte');
        $commerce = $option->attr('value');
        $form['search_advanced[commerce]']->select($commerce);

        $crawler = $this->anonyme->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("Anglais")')->count()
        );

        $crawler = $this->admin->click($crawler->selectLink('Anglais')->link());
        $this->assertEquals(200, $this->anonyme->getResponse()->getStatusCode());
    }

    public function t2estSearchLogisticien()
    {
        $url = '/admin/logisticien/archive/';
        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $form = $crawler->selectButton('Rechercher')->form(
            [
                'search_logisticien[user]' => 'zora',
              //  'search_logisticien[idcommande]' => 111,
            ]
        );

        $option = $crawler->filter('#search_logisticien_commerce option:contains("Friterie Enka")');
        $this->assertEquals(1, count($option), 'enka');
        $commerce = $option->attr('value');
        $form['search_logisticien[commerce]']->select($commerce);

        $option = $crawler->filter('#search_logisticien_lieu_livraison option:contains("Wex")');
        $this->assertEquals(1, count($option), 'Wex');
        $lieu = $option->attr('value');
        $form['search_logisticien[lieu_livraison]']->select($lieu);

        $crawler = $this->logisticien->submit($form);

        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("Anglais")')->count()
        );

        $this->assertEquals(200, $this->anonyme->getResponse()->getStatusCode());
    }

}
