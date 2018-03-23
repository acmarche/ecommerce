<?php

namespace App\Tests\Admin\Access;

use App\Tests\BaseUnit;

class DefaultControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCategorie()
    {
        $url = '/admin/categorie/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testLieuxLivraison()
    {
        $url = '/admin/lieu_livraison/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }


}
