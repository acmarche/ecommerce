<?php

namespace App\Tests\Admin\Controller;

use App\Tests\BaseUnit;

class CommerceControllerTest extends BaseUnit
{
    public function testNew()
    {
        $url = '/admin/commerce/';

        $this->executeUrl($url, $this->admin, 200);

        $url = '/admin/commerce/new';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'commerce[nom]' => 'La grenouill',
                'commerce[numeroTva]' => '12345678',
                'commerce[iban]' => 'FR7614410000011234567890163',
                'commerce[sms_numero]' => '32476662615',
                'commerce[emailCommande]' => 'jf@marche.be',
            ]
        );

        $option = $crawler->filter('#commerce_bottinId option:contains("LA GRENOUILLE")');
        $this->assertEquals(1, count($option), 'LA GRENOUILLE');
        $fiche = $option->attr('value');
        $form['commerce[bottinId]']->select($fiche);

        $form['commerce[imageFile][file]']->upload('/home/jfsenechal/Images/chouette.jpg');

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("La grenouill")')->count()
        );
    }

    public function testEdit()
    {
        $url = '/admin/commerce/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('La grenouill')->link());

        $this->assertGreaterThan(0, $crawler->filter('h3:contains("La grenouill")')->count());

        $crawler = $this->admin->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Mettre à jour')->form(
            [
                'commerce[nom]' => 'La grenouille',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("La grenouille")')->count()
        );
    }

}
