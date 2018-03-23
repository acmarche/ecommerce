<?php

namespace App\Controller\Admin;

use App\Entity\Commande\Commande;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @Route("/admin")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE') or has_role('ROLE_ECOMMERCE_LOGISTICIEN')")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="acecommerce_admin_home")
     * @Template()
     */
    public function index()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            $commandes = $em->getRepository(Commande::class)->search(['paye' => 2]);
         } elseif ($user->hasRole('ROLE_ECOMMERCE_COMMERCE')) {
            $commandes = $em->getRepository(Commande::class)->getCommandeAValider($user);
        } else {
            $commandes = $em->getRepository(Commande::class)->getCommandeALivrer();
        }

        return [
            'commandes' => $commandes,
        ];

    }

    /**
     * @Route("/doc", name="acecommerce_admin_doc")
     * @Template()
     */
    public function doc()
    {
        return [

        ];
    }
}
