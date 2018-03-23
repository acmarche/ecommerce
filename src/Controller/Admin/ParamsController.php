<?php

namespace App\Controller\Admin;

use App\Entity\Params;
use App\Form\ParamsType;
use App\Service\ParamsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * params controller.
 *
 * @Route("/admin/params")
 * @Security("has_role('ROLE_ECOMMERCE_ADMIN')")
 */
class ParamsController extends AbstractController
{
    /**
     * Lists all params entities.
     *
     * @Route("/", name="acecommerce_admin_params_index")
     * @Method("GET")
     * @Template()
     */
    public function index(ParamsService $paramsUtil)
    {

       $defaultTva = $paramsUtil->getDefaultTva();
       $emailMaster = $paramsUtil->getEmailMaster();
       $stripePublic = $paramsUtil->getStripePublicKey();
       $stripeSecret = $paramsUtil->getStripeSecretKey();

        return [
            'defaultTva' => $defaultTva,
            'emailMaster' => $emailMaster,
            'stripePublic' => $stripePublic,
            'stripeSecret' => $stripeSecret,
        ];
    }

    /**
     * Displays a form to edit an existing params entity.
     *
     * @Route("/{id}/edit", name="acecommerce_admin_params_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function edit(Request $request, Params $params)
    {
        $editForm = $this->createForm(ParamsType::class, $params);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bin été modifiée');

            return $this->redirectToRoute('acecommerce_admin_params_index');
        }

        return [
            'params' => $params,
            'edit_form' => $editForm->createView(),
        ];

    }
}
