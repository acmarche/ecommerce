<?php

namespace App\Event;

use App\Entity\InterfaceDef\CommandeInterface;
use App\Service\EcommerceConstante;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 12:05
 */
class PanierEvent extends Event
{
    const PANIER_INDEX = EcommerceConstante::PANIER_INDEX;
    protected $commandes;
    /**
     * @var Response
     */
    private $response;
    private $request;

    /**
     * PanierEvent constructor.
     * @param $commandes CommandeInterface[]
     */
    public function __construct($commandes, Request $request)
    {
        $this->commandes = $commandes;
        $this->request = $request;
    }

    /**
     * @return CommandeInterface[]
     */
    public function getCommandes(): array
    {
        return $this->commandes;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}