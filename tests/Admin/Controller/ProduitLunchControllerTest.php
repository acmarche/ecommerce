<?php

namespace App\Tests\Admin\Controller;

use App\Tests\BaseUnit;

class ProduitLunchControllerTest extends BaseUnit
{
    public function testAdd()
    {
        $url = '/admin/commerce/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('La grenouille')->link());
        $crawler = $this->admin->click($crawler->selectLink('de lunch')->link());

        $form = $crawler->selectButton('Ajouter')->form(
            [
                'produit_lunch[nom]' => 'Frite mayonaise',
                'produit_lunch[prixHtva]' => 4.30,
                'produit_lunch[description]' => 'Plein de sauce et de gras',
            ]
        );

        $option = $crawler->filter('#produit_lunch_categorie option:contains("Lunchs chauds")');
        $this->assertEquals(1, count($option), 'Lunchs chauds');
        $categorie = $option->attr('value');
        $form['produit_lunch[categorie]']->select($categorie);

        $form['produit_lunch[imageFile][file]']->upload('/home/jfsenechal/Images/chouette.jpg');

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('div:contains("Le produit a bien été ajouté")')->count()
        );
    }

    public function testEdit()
    {
        $url = '/admin/produit/';
        $crawler = $this->executeUrl($url, $this->admin, 200);

        $crawler = $this->admin->click($crawler->selectLink('Frite mayonaise')->link());
        $crawler = $this->admin->click($crawler->selectLink('Editer')->link());

        $form = $crawler->selectButton('Mettre à jour')->form(
            [
                'produit_lunch[nom]' => 'Frite mayonnaise',
            ]
        );

        $this->admin->submit($form);
        $crawler = $this->admin->followRedirect();

        $this->assertGreaterThan(
            0,
            $crawler->filter('h3:contains("Frite mayonnaise")')->count()
        );
    }

}
