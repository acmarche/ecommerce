<?php

namespace App\Entity\Stripe;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * StripeCharge
 *
 * @ORM\Table(name="stripe_charge")
 * @ORM\Entity(repositoryClass="App\Repository\Stripe\StripeChargeRepository")
 */
class StripeCharge
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $id_charge;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $stripe_token;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $stripe_token_type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $stripe_email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $client_ip;

    function __toString()
    {
        return $this->id_charge;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdCharge(): string
    {
        return $this->id_charge;
    }

    /**
     * @param string $id_charge
     */
    public function setIdCharge(string $id_charge): void
    {
        $this->id_charge = $id_charge;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getStripeToken(): string
    {
        return $this->stripe_token;
    }

    /**
     * @param string $stripe_token
     */
    public function setStripeToken(string $stripe_token): void
    {
        $this->stripe_token = $stripe_token;
    }

    /**
     * @return string
     */
    public function getStripeTokenType(): string
    {
        return $this->stripe_token_type;
    }

    /**
     * @param string $stripe_token_type
     */
    public function setStripeTokenType(string $stripe_token_type): void
    {
        $this->stripe_token_type = $stripe_token_type;
    }

    /**
     * @return string
     */
    public function getStripeEmail(): string
    {
        return $this->stripe_email;
    }

    /**
     * @param string $stripe_email
     */
    public function setStripeEmail(string $stripe_email): void
    {
        $this->stripe_email = $stripe_email;
    }

    /**
     * @return string
     */
    public function getClientIp(): string
    {
        return $this->client_ip;
    }

    /**
     * @param string $client_ip
     */
    public function setClientIp(string $client_ip): void
    {
        $this->client_ip = $client_ip;
    }

}
