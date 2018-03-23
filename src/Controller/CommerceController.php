<?php

namespace App\Controller;

use App\Entity\Commerce\Commerce;
use App\Form\Commerce\ContactCommerceType;
use App\Service\Bottin;
use App\Service\MailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommerceController
 * @package App\Controller
 * @Route("/commerces")
 */
class CommerceController extends AbstractController
{
    /**
     * Lists all commerce entities.
     *
     * @Route("/", name="acecommerce_commerce_index")
     * @Method("GET")
     * @Template()
     */
    public function index(Bottin $bottin)
    {
        $em = $this->getDoctrine()->getManager();

        $commerces = $em->getRepository(Commerce::class)->search(['rand' => true]);
        try {
            $bottin->populateMetas($commerces);
        } catch (\Exception $exception) {

        }

        return [
            'commerces' => $commerces,
        ];
    }

    /**
     * @Route("/{id}", name="acecommerce_commerce_show")
     * @Method({"GET","POST"})
     * @Template()
     * @param Request $request
     * @param Commerce $commerce
     * @param Bottin $bottin
     * @param MailerService $mailer
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show(Request $request, Commerce $commerce, Bottin $bottin, MailerService $mailer)
    {
        if ($commerce->getIndisponible()) {
            throw $this->createNotFoundException("Le commerce n'est pas disponible ");
        }

        $error = $fiche = false;
        try {
            $fiche = $bottin->getFiche($commerce->getBottinId());
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $em = $this->getDoctrine()->getManager();
        $next = $em->getRepository(Commerce::class)->getNext($commerce);
        $previous = $em->getRepository(Commerce::class)->getPrevious($commerce);

        $user = $this->getUser();
        $data = [];
        if ($user && $user->hasRole('ROLE_ECOMMERCE_CLIENT')) {
            $data['nom'] = $user->getPrenom().' '.$user->getNom();
            $data['email'] = $user->getEmail();
        }
        $form = $this->createForm(ContactCommerceType::class, $data)
        ->add('submit',SubmitType::class,['label'=>'Envoyer']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailer->sendNewContact($commerce, $form);
            $this->addFlash('success', 'email envoyé');

            return $this->redirectToRoute('acecommerce_commerce_show', ['id' => $commerce->getId()]);
        }

        return [
            'fiche' => $fiche,
            'commerce' => $commerce,
            'next' => $next,
            'previous' => $previous,
            'form' => $form->createView(),
        ];
    }
}
