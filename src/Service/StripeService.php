<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/09/17
 * Time: 14:53
 */

namespace App\Service;

use App\Entity\Client\Client;
use App\Entity\Commande\Commande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\Security\User;
use App\Entity\Stripe\StripeCharge;
use Doctrine\Common\Persistence\ObjectManager;
use Stripe\Charge;
use Stripe\Stripe;

class StripeService
{
    private $stripeSecret;
    private $stripePublic;
    private $manager;
    private $paramsService;
    private $tvaService;
    private $prixSerice;

    /**
     * StripeService constructor.
     */
    public function __construct(
        ParamsService $paramsService,
        ObjectManager $manager,
        PrixService $prixService,
        TvaService $tvaService,
        string $stripeSecret,
        string $stripePublic
    ) {
        $this->paramsService = $paramsService;
        $this->stripeSecret = $stripeSecret;
        $this->stripePublic = $stripePublic;
        $this->manager = $manager;
        $this->tvaService = $tvaService;
        $this->prixSerice = $prixService;
    }

    /**
     * @return string
     */
    public function getStripeSecret(): string
    {
        return $this->stripeSecret;
    }

    /**
     * @return string
     */
    public function getStripePublic(): string
    {
        return $this->stripePublic;
    }

    /**
     * Frais transaction
     * 1,4 %  + 0,25 €
     * https://stripe.com/be/pricing
     * @param $montant
     * @return float
     */
    public function calculFraisTransaction($montant, $taux = 1.24, $charge = 0.25)
    {
        $frais = $this->tvaService->calculTva($montant, $taux);

        return $frais + $charge;
    }

    /**
     * @param string $token
     * @param CommandeInterface $commande
     * @param string $stripeEmail
     * @return \Stripe\ApiResource
     * @throws \Exception
     */
    public function chargeCard(string $token, CommandeInterface $commande, string $stripeEmail)
    {
        //set  your secret key: remember to change this to your live secret key in Produition
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        Stripe::setApiKey($this->stripeSecret);

        $cout = $commande->getCout();

        if (!$cout) {
            throw new \Exception(
                'Montant introuvable'
            );
        }

        $description = "Commande ".$commande->getId()." chez ".$commande->getCommerce()->getNom();

        $amount = $cout->getTotalInCents();

        //http://www.theodo.fr/blog/2017/10/add-online-payment-on-symfony-project/
        //todo ?
        //$this->sendCharge($amount, $description, $token, $stripeEmail);
        $charge = Charge::create(
            array(
                "amount" => $amount,
                "currency" => "eur",
                "description" => $description,
                "source" => $token,
                'receipt_email' => $stripeEmail,
            )
        );

        return $charge;
    }

    /**
     * @param \Stripe\ApiResource $charge
     * @param CommandeInterface $commande
     * @param string $stripeToken
     * @param string $stripeTokenType
     * @param string $stripeEmail
     * @param null|string $ipClient
     * @return StripeCharge
     */
    public function createEntityCharge(
        \Stripe\ApiResource $charge,
        CommandeInterface $commande,
        string $stripeToken,
        string $stripeTokenType,
        string $stripeEmail,
        ?string $ipClient
    ) {
        $stripeCharge = new StripeCharge();
        $stripeCharge->setIdCharge($charge->id);
        $stripeCharge->setUser($commande->getUser());
        $stripeCharge->setStripeToken($stripeToken);
        $stripeCharge->setStripeTokenType($stripeTokenType);
        $stripeCharge->setStripeEmail($stripeEmail);
        $stripeCharge->setClientIp($ipClient);

        $this->manager->persist($stripeCharge);
        $commande->setStripeCharge($stripeCharge);

        $this->manager->flush();

        return $stripeCharge;
    }

    /**
     * @return \Stripe\Collection
     */
    public function getAllCharges(int $max = 20)
    {
        Stripe::setApiKey($this->stripeSecret);

        return Charge::all(array("limit" => $max));
    }

    /**
     * @param string|array $id
     * @return \Stripe\StripeObject
     */
    public function getChargeById($id)
    {
        Stripe::setApiKey($this->stripeSecret);

        return Charge::retrieve($id);
    }


    public function chargeAndCreateClient($token, $montant, User $user)
    {
        // Set your secret key: remember to change this to your live secret key in Produition
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        Stripe::setApiKey($this->stripeSecret);

        // Create a Customer:
        $customer = \Stripe\Customer::create(
            array(
                "email" => "paying.user@example.com",
                "source" => $token,
            )
        );

        // Charge the Customer instead of the card:
        $charge = Charge::create(
            array(
                "amount" => $montant,
                "currency" => "eur",
                "customer" => $customer->id,
            )
        );

        // YOUR CODE: Save the customer ID and other info in a database for later.
        $client = new Client();
        $client->setSource($token);
        $client->setEmail("paying.user@example.com");
        $client->setStripeId($customer->id);
        $client->setUser($user->getUsername());

        $this->manager->persist($client);
        // $em->flush();
    }


    protected function chargeClient($token, $montant, $customerId)
    {
        // YOUR CODE (LATER): When it's time to charge the customer again, retrieve the customer ID.
        $charge = Charge::create(
            array(
                "token" => $token,
                "amount" => $montant, // $15.00 this time
                "currency" => "eur",
                "customer" => $customerId,
            )
        );

        return $charge;
    }

    /**
     * @param int $amount
     * @param string $description
     * @param string $token
     * @param string $stripeEmail
     * @return \Stripe\ApiResource
     * @throws \Exception
     */
    protected function sendCharge(int $amount, string $description, string $token, string $stripeEmail)
    {
        try {
            $charge = Charge::create(
                array(
                    "amount" => $amount,
                    "currency" => "eur",
                    "description" => $description,
                    "source" => $token,
                    'receipt_email' => $stripeEmail,
                )
            );

            return $charge;
        } catch (\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err = $body['error'];

            print('Status is:'.$e->getHttpStatus()."\n");
            print('Type is:'.$err['type']."\n");
            print('Code is:'.$err['code']."\n");
            // param is '' in this case
            print('Param is:'.$err['param']."\n");
            print('Message is:'.$err['message']."\n");
        } catch (\Stripe\Error\RateLimit $e) {
            throw new \Exception("Too many requests made to the API too quickly");
        } catch (\Stripe\Error\InvalidRequest $e) {
            throw new \Exception(" Invalid parameters were supplied to Stripe's API");
        } catch (\Stripe\Error\Authentication $e) {
            throw new \Exception("Authentication with Stripe's API failed");
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            throw new \Exception(" Network communication with Stripe failed");
        } catch (\Stripe\Error\Base $e) {
            throw new \Exception($e->getMessage());
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}