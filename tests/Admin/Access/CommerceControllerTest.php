<?php

namespace App\Tests\Admin\Access;

use App\Tests\BaseUnit;

class CommerceControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/commerce/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowCommerceEnka()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);

        $url = '/admin/commerce/' . $commerce->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowCommercePorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);

        $url = '/admin/commerce/' . $commerce->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditCommerceEnka()
    {
        $commerce = $this->getCommerce(['user' => 'enka']);

        $url = '/admin/commerce/' . $commerce->getId() . '/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditCommercePorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);

        $url = '/admin/commerce/' . $commerce->getId() . '/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceMalice, 200);
        $this->executeUrl($url, $this->commerceEnka, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }


}
