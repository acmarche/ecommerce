<?php

namespace App\Controller\Admin;

use App\Entity\Commande\Commande;
use App\Entity\Commerce\Commerce;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Form\Search\SearchCommandeType;
use App\Service\CommandeCoutService;
use App\Service\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * commande controller.
 *
 * @Route("/admin/commande")
 *
 */
class CommandeController extends AbstractController
{
    /**
     * Lists all commande entities.
     *
     * @Route("/", name="acecommerce_admin_commande_index")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            $commandes = $em->getRepository(Commande::class)->getCommandeALivrer();
        } else {
            $commandes = $em->getRepository(Commande::class)->getCommandeALivrerByCommerce($user);
        }

        return [
            'commandes' => $commandes,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="acecommerce_admin_commande_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('show', commande)")
     */
    public function show(Commande $commande, CommandeCoutService $commandeCoutService, StripeService $stripeService)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $cout = $commandeCoutService->getCoutsCommande($commande);
        $charge = false;

        try {
            if ($commande->getStripeCharge()) {
                $charge = $stripeService->getChargeById($commande->getStripeCharge()->getIdCharge());
            }
        } catch (\Exception $exception) {
            $this->addFlash('warning', 'Impossible d\'obtenir les informations stripe');
        }

        return [
            'commande' => $commande,
            'cout' => $cout,
            'charge' => $charge,
            'delete_form' => $deleteForm->createView(),
        ];
    }


    /**
     * Deletes a commande entity.
     *
     * @Route("/{id}", name="acecommerce_admin_commande_delete")
     * @Method("DELETE")
     * @Security("is_granted('DELETE', commande)")
     */
    public function delete(Request $request, CommandeInterface $commande)
    {
        $form = $this->createDeleteForm($commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();
        }

        return $this->redirectToRoute('acecommerce_admin_commande_index');
    }

    /**
     * Creates a form to delete a commande entity.
     *
     * @param Commande $commande The commande entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(CommandeInterface $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_admin_commande_delete', array('id' => $commande->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }

    /**
     * Liste des commandes livrées
     *
     * @Route("/archive/", name="acecommerce_admin_commande_livre")
     * @Method("GET")
     * @Security("has_role('ROLE_ECOMMERCE_COMMERCE') or has_role('ROLE_ECOMMERCE_LOGISTICIEN')")
     * @Template()
     */
    public function livre(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = $request->getSession();
        $key = "commande_search";
        $search = false;
        $args = [
            'paye' => 1,
            'livre' => 1,
        ];

        if ($session->has($key)) {
            $search = true;
            $args = unserialize($session->get($key));
        }

        $form = $this->createForm(
            SearchCommandeType::class,
            $args,
            [
                'method' => 'GET',
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('raz')->isClicked()) {
                $session->remove($key);
                $this->addFlash('info', 'La recherche a bien été réinitialisée.');

                return $this->redirectToRoute('acecommerce_admin_commande_livre');
            }

            $search = true;
            $args = $form->getData();
            $commerces = [];
            if ($user->hasRole('ROLE_ECOMMERCE_COMMERCE') and !isset($args['commerce'])) {
                foreach ($em->getRepository(Commerce::class)->getCommercesOwnedByUser(
                    $user
                ) as $commerce) {
                    $commerces[] = $commerce->getId();
                }
                $args['commerces'] = $commerces;
            }

            $session->set($key, serialize($args));
        }

        $commandes = $em->getRepository(Commande::class)->search($args);

        return [
            'search' => $search,
            'search_form' => $form->createView(),
            'commandes' => $commandes,
        ];
    }
}
