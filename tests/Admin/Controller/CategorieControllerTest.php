<?php

namespace App\Tests\Admin\Controller;

use App\Tests\BaseUnit;

class CategorieControllerTest extends BaseUnit
{
    public function testAdd()
    {
        $url = '/admin/categorie/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Ajouter')->link());

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'categorie[nom]' => 'Crace',
                'categorie[description]' => 'Trucs sucré et gras',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("Crace")')->count()
        );
    }

    public function testEdit()
    {
        $url = '/admin/categorie/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Crace')->link());
        $crawler = $this->admin->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Mettre à jour')->form(
            [
                'categorie[nom]' => 'Crasses',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("Crasses")')->count()
        );
    }

    public function testAddWithImage()
    {
        $url = '/admin/categorie/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Ajouter')->link());

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'categorie[nom]' => 'Cat img'
            ]
        );

        $form['categorie[imageFile][file]']->upload('/home/jfsenechal/Images/chouette.jpg');

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("Cat img")')->count()
        );
    }

}
