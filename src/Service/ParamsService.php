<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/09/17
 * Time: 14:53
 */

namespace App\Service;

use App\Entity\Params;
use Doctrine\Common\Persistence\ObjectManager;

class ParamsService
{
    private $manager;

    /**
     * StripeUtil constructor.
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return float
     */
    public function getDefaultTva()
    {
        $manager = self::getManager();
        $tva = $manager->getRepository(Params::class)->findOneBy(['nom' => 'default_tva']);
        if ($tva) {
            return $tva->getValeur();
        }

        return 0;
    }

    /**
     * @return string
     */
    public function getEmailMaster()
    {
        $manager = self::getManager();
        $email = $manager->getRepository(Params::class)->findOneBy(['nom' => 'email_master']);
        if ($email) {
            return $email->getValeur();
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getStripeSecretKey()
    {
        $manager = self::getManager();
        $key = $manager->getRepository(Params::class)->findOneBy(['nom' => 'stripe_secret_key']);
        if ($key) {
            return $key->getValeur();
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getStripePublicKey()
    {
        $manager = self::getManager();
        $key = $manager->getRepository(Params::class)->findOneBy(['nom' => 'stripe_public_key']);
        if ($key) {
            return $key->getValeur();
        }

        return null;
    }

    public function getSmsLogin()
    {
        $manager = self::getManager();
        $key = $manager->getRepository(Params::class)->findOneBy(['nom' => 'sms_login']);
        if ($key) {
            return $key->getValeur();
        }

        return null;
    }

    public function getSmsMdp()
    {
        $manager = self::getManager();
        $key = $manager->getRepository(Params::class)->findOneBy(['nom' => 'sms_mdp']);
        if ($key) {
            return $key->getValeur();
        }

        return null;
    }

    public function getUnitePoids()
    {
        $manager = self::getManager();

        return $manager->getRepository(Params::class)->findBy(['nom' => 'poids_unite']);
    }

}