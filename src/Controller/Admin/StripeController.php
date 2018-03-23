<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 21/03/18
 * Time: 18:30
 */

namespace App\Controller\Admin;

use App\Service\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * stripe controller.
 *
 * @Route("/admin/stripe")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class StripeController extends AbstractController
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="acecommerce_admin_charges_index")
     * @Method("GET")
     *
     */
    public function index(StripeService $stripeService)
    {
        $collectionCharges = $stripeService->getAllCharges();

        return $this->render(
            'admin/stripe/index.html.twig',
            [
                'collectionCharges' => $collectionCharges,
            ]
        );
    }
}