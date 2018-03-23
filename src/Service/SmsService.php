<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/09/17
 * Time: 14:53
 */

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SmsService
{
    private $client;
    private $twig_Environment;
    private $apiUrl = "https://ecom.inforius.be/Api/";//todo https ?
    private $user;
    private $password;

    public function __construct(\Twig_Environment $twigEnvironment, ParamsService $paramsUtil)
    {
        $this->twig_Environment = $twigEnvironment;
        $this->user = $paramsUtil->getSmsLogin();
        $this->password = $paramsUtil->getSmsMdp();
        $http_options = [
            'timeout' => 10,
        ];

        $this->client = new Client($http_options);
    }

    public function send($numero, $message)
    {
        $result = $this->sendMessage($numero, $message);

        if ($result instanceof \SimpleXMLElement) {
            $messages = $result->Messages;
            $statut = $messages->MessageStatus;
            $errorCode = $statut->ErrorCode;
            $message = (string)$statut->ErrorMessage;

            if ($errorCode > 0) {

            }
        }
    }

    public function getToken()
    {
        $action = "RequestToken";
        try {
            $message = $this->twig_Environment->render(
                '@App/Sms/token.txt.twig',
                [
                    'user' => $this->user,
                    'password' => $this->password,
                ]
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }

        $result = $this->doCurl($action, $message);

        $xml = simplexml_load_string($result);
        $error = (string)$xml->Error;
        if ($error) {
            var_dump($error);
        }
        $expiration = $xml->Expiration;

        return (string)$xml->Token;
    }

    public function sendMessage($numero, $message)
    {
        /**
         * Sms.php on line 49:
         * "%26%23043%3B32476662615"
         * "%26%23043%3B476662615"
         */
        $token = $this->getToken();
        $numeroOk = "&#043;".$numero;
        //$numero = urlencode("&#043;32476662615");
        $numero = urlencode($numeroOk);

        $message = $this->twig_Environment->render(
            '@App/Sms/message.txt.twig',
            [
                'user' => $this->user,
                'message' => $message,
                'numero' => $numero,
                'token' => $token,
            ]
        );

        $action = 'Send';
        $result = $this->doCurl($action, $message);
        $xml = simplexml_load_string($result);

        return $xml;
    }

    public function close($token)
    {
        //destroy token
        $action = "ReleaseToken?access_token=$token";
        $message = "";
        $this->doCurl($action, $message);
    }

    public function doCurl($action, $xml)
    {
        $settings = array(
            CURLOPT_URL => $this->apiUrl.$action,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $xml,
        );
        $curl = curl_init();
        curl_setopt_array($curl, $settings);
        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
        }

        curl_close($curl);

        return trim($result);
    }

    protected function request($method, $path)
    {
        // Set default request options with auth header.
        $options = [
            'headers' => [
                'Authorization' => $this->user.' '.$this->password,
            ],
        ];

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