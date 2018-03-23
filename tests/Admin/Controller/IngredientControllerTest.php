<?php

namespace App\Tests\Admin\Controller;

use App\Tests\BaseUnit;

class IngredientControllerTest extends BaseUnit
{
    public function testAdd()
    {
        $url = '/admin/commerce/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('La grenouille')->link());
        $crawler = $this->admin->click($crawler->selectLink('Ajouter un ingrédient')->link());

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'ingredient[nom]' => 'Patatte',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('div:contains("L\'ingrédient a bien été ajouté")')->count()
        );
    }

    public function testEdit()
    {
        $url = '/admin/ingredient/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

     //   $crawler = $this->admin->click($crawler->selectLink('La grenouille')->link());
        $crawler = $this->admin->click($crawler->selectLink('Patatte')->link());
        $crawler = $this->admin->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Mettre à jour')->form(
            [
                'ingredient[nom]' => 'Patate',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("Patate")')->count()
        );
    }

}
