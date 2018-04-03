<?php

namespace App\Service;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/07/17
 * Time: 16:29
 */
use App\Entity\Commerce\Commerce;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class Bottin
{
    private $user;
    private $password;
    private $client;
    private $baseUrl;
    private $cache;

    public function __construct($url, $user, $password, AdapterInterface $cacheAdapter)
    {
        $this->baseUrl = $url;

        $this->user = $user;
        $this->password = $password;

        //todo in 4.1 Psr\SimpleCache\CacheInterface
        $this->cache = $cacheAdapter;

        $http_options = [
            'timeout' => 10,
        ];

        $this->client = new Client($http_options);
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getFiches()
    {
        $url = "/fiches";
        $key = md5('bottin.allfiche');

        $item = $this->cache->getItem($key);
        if ($item->isHit()) {
            $data = $this->request("GET", $url);
            $item->set($data);
            $this->cache->save($item);
        }

        return $item->get();
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getFiche($ficheId)
    {
        $url = "/fiche/".$ficheId;
        $key = md5('bottin.fiche.'.$ficheId);

        $item = $this->cache->getItem($key);
        if ($item->isHit()) {
            $data = $this->request("GET", $url);
            $item->set($data);
            $this->cache->save($item);
        }

        return $item->get();
    }

    public function getHoraire($ficheId)
    {
        $fiche = $this->getFiche($ficheId);

        return isset($fiche->horaires) ? $fiche->horaires : [];
    }

    public function getFichesByCategorie($categoryId)
    {
        $url = "/fiches/rubrique/".$categoryId;

        return $this->request("GET", $url);
    }

    public function getFichesForForm()
    {
        $fiches = [];
        foreach ($this->getFichesByCategorie(523) as $fiche) {
            $fiches[$fiche->societe] = $fiche->id;
        }

        return $fiches;
    }

    /**
     * @param Commerce[] $commerces
     */
    public function populateMetas($commerces)
    {
        foreach ($commerces as $commerce) {
            $fiche = $this->getFiche($commerce->getBottinId());
            $commerce->setMetas($fiche);
        }
    }

    protected function request($method, $path)
    {
        // Set default request options with auth header.
        $options = [
            'headers' => [
                'Authorization' => $this->user.' '.$this->password,
            ],
        ];
        $options['auth'] = [$this->user, $this->password];
        // Add trigger error header if a debug error code has been set.
        if (!empty($this->debug_error_code)) {

            $options['headers']['X-Trigger-Error'] = $this->debug_error_code;
        }

        return $this->handleRequest($method, $this->baseUrl.$path, $options);

    }

    /**
     * Makes a request to the MailChimp API using the Guzzle HTTP client.
     *
     * @see Mailchimp::request().
     */
    public function handleRequest($method, $uri = '', $options = [], $parameters = [])
    {
        if (!empty($parameters)) {
            if ($method == 'GET') {
                // Send parameters as query string parameters.
                $options['query'] = $parameters;
            } else {
                // Send parameters as JSON in request body.
                $options['json'] = (object)$parameters;
            }
        }

        try {

            $response = $this->client->request($method, $uri, $options);
            $data = json_decode($response->getBody());

            return $data;
        } catch (RequestException $e) {

            $response = $e->getResponse();
            $message = $e->getMessage();

            /* if (!empty($response)) {
                 $message = $e->getResponse()->getBody();
             } else {
                 $message = $e->getMessage();
             }*/

            throw new \Exception($message, $e->getCode(), $e);
        }
    }

}