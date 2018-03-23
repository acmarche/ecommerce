<?php

namespace App\Tests\Admin\Controller;

use App\Tests\BaseUnit;

class AttributsControllerTest extends BaseUnit
{

    public function testAdd()
    {
        $url = '/admin/commerce/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('La grenouille')->link());
        $crawler = $this->admin->click($crawler->selectLink('Ajouter un supplément')->link());

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'supplement[nom]' => 'Grosse porsion',
                'supplement[prix]' => 0.30
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('div:contains("Le supplément a bien été ajouté")')->count()
        );
    }

    public function testEdit()
    {
        $url = '/admin/supplement/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Grosse porsion')->link());
        $crawler = $this->admin->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Mettre à jour')->form(
            [
                'supplement[nom]' => 'Grosse portion',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("Grosse portion")')->count()
        );
    }

}
