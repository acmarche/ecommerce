<?php

namespace App\Controller;

use App\Entity\Produit\Produit;
use App\Event\PanierEvent;
use App\Form\Commande\CommandeProduitType;
use App\Manager\CommandeProduitManager;
use App\Manager\PanierManager;
use App\Repository\Commande\CommandeProduitRepository;
use App\Service\CommandeCoutService;
use App\Service\RequestService;
use Doctrine\ORM\NonUniqueResultException;
use App\Service\PanierService;
use App\Service\TvaService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PanierController
 * @package App\Controller
 * @Route("/panier")
 *
 */
class PanierController extends AbstractController
{
    private $panierManager;
    private $commandeCoutService;

    public function __construct(
        PanierManager $panierManager,
        CommandeCoutService $commandeCoutService
    ) {
        $this->panierManager = $panierManager;
        $this->commandeCoutService = $commandeCoutService;
    }

    /**
     * @Route("/", name="acecommerce_panier")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     * @Template()
     */
    public function index(
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        TvaService $tvaService
    ) {
        $ruptures = $indisponibles = $commandes = null;

        $formDelete = $this->createDeleteForm();
        $formVider = $this->createViderForm();
        $formUpdate = $this->createUpdateForm();
        $formCommentaire = $this->createCommentaireForm();

        $commandes = $this->panierManager->getPanierEncours();
        //todo mettre dans event redirect ?
        if (count($commandes) == 0) {
            $this->addFlash('info', 'Votre panier est vide');

            return $this->redirectToRoute('acecommerce_home');
        }

        $panierEvent = new PanierEvent($commandes, $request);
        $eventDispatcher->dispatch(PanierEvent::PANIER_INDEX, $panierEvent);

        $this->commandeCoutService->bindCouts($commandes);

        foreach($commandes as $comm){
            foreach($comm->getCommandeProduits() as $comProd){
                $comProd->setPrixTvac($tvaService->getPrixProduitTvac($comProd->getProduit()));
            }
        }
        $json = json_encode($commandes);

        return [
            'ruptures' => $ruptures,
            'indisponibles' => $indisponibles,
            'commandes' => $commandes,
            'form_update' => $formUpdate->createView(),
            'form_vider' => $formVider->createView(),
            'form_delete' => $formDelete->createView(),
            'form_commentaire' => $formCommentaire->createView(),
            'json' => $json,
        ];
    }

    /**
     * Ajoute un produit dans le panier
     *
     * @Route("/add/{id}", name="acecommerce_panier_add")
     * @Method("POST")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     */
    public function add(
        Request $request,
        Produit $produit,
        CommandeProduitManager $commandeProduitManager,
        RequestService $requestService
    ) {
        $user = $this->getUser();
        //todo meme code dans produitcontroller
        try {
            $commandeProduit = $commandeProduitManager->getOrInitCommandeProduit($produit, $user);
        } catch (NonUniqueResultException $e) {
            $this->addFlash('error', $e->getMessage());
            $this->redirectToRoute('acecommerce_produit_index');
        }

        $form = $this->createForm(CommandeProduitType::class, $commandeProduit)
            ->add('submit', SubmitType::class, ['label' => 'Ajouter']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $attributs = $requestService->getAttributs($request, $produit);
            $quantite = intval($form->getData()->getQuantite());

            try {
                $this->panierManager->addProduit($produit, $quantite, $attributs);
                $mot = " ajouté";
                if ($quantite > 1) {
                    $mot = " ajoutés";
                }
                $this->addFlash('success', "Produit ".$mot);
            } catch (\Exception $exception) {
                $this->addFlash('warning', $exception->getMessage());
            }

        }

        return $this->redirectToRoute('acecommerce_produit_show', ['id' => $produit->getId()]);
    }

    /**
     * Update depuis le panier
     *
     * @Route("/update/", name="acecommerce_panier_update")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function update(Request $request, CommandeProduitRepository $commandeProduitRepository)
    {
        $quantite = intval($request->request->get('quantiteProduit'));

        $commandeProduitId = intval($request->request->get('idCommandeProduit'));
        $commandeProduit = $commandeProduitRepository->find($commandeProduitId);
        $this->denyAccessUnlessGranted('edit', $commandeProduit, "Vous n'avez pas accès a cette commande.");

        $attributs = $request->request->get('attributs', []);

        try {
            $this->panierManager->updateProduit($commandeProduit, $quantite, $attributs);
        } catch (\Exception $exception) {
            return new JsonResponse(['data' => ['error' => $exception->getMessage()]]);
        }

        $commandes = $this->panierManager->getPanierEncours();
        $this->commandeCoutService->bindCouts($commandes);

        return new JsonResponse(
            [
                'data' => [
                    'html' => $this->renderView('panier/_details.html.twig', ['commandes' => $commandes]),
                ],
            ]
        );
    }

    /**
     * Update depuis le panier
     *
     * @Route("/updateJSON/", name="acecommerce_panier_update_json")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function updateJson(Request $request, CommandeProduitRepository $commandeProduitRepository,TvaService $tvaService)
    {
        $quantite = intval($request->request->get('quantiteProduit'));

        $commandeProduitId = intval($request->request->get('idCommandeProduit'));
        $commandeProduit = $commandeProduitRepository->find($commandeProduitId);
        $this->denyAccessUnlessGranted('edit', $commandeProduit, "Vous n'avez pas accès a cette commande.");

        $attributs = $request->request->get('attributs', []);

        try {
            $this->panierManager->updateProduit($commandeProduit, $quantite, $attributs);
        } catch (\Exception $exception) {
            return new JsonResponse(['data' => ['error' => $exception->getMessage()]]);
        }

        $commandes = $this->panierManager->getPanierEncours();
        $this->commandeCoutService->bindCouts($commandes);

        foreach($commandes as $comm){
            foreach($comm->getCommandeProduits() as $comProd){
                $comProd->setPrixTvac($tvaService->getPrixProduitTvac($comProd->getProduit()));
            }
        }
        $json = json_encode($commandes);

        return new JsonResponse(
            [
                'orders'=>$json
            ]
        );
    }

    /**
     *
     * @Route("/delete", name="acecommerce_panier_produit_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     */
    public function delete(Request $request, CommandeProduitRepository $commandeProduitRepository)
    {
        $commandeProduitId = $request->request->get('commandeProduit');
        $commandeProduit = $commandeProduitRepository->find($commandeProduitId);

        if (!$commandeProduit) {
            $this->addFlash('danger', 'produit introuvable');

            return $this->redirectToRoute('acecommerce_panier');
        }

        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->denyAccessUnlessGranted('delete', $commandeProduit, "Vous n'avez pas accès a cette commande.");

            $this->panierManager->removeProduit($commandeProduit);

            $this->addFlash('success', 'Le produit a bien été supprimé du panier.');
        }

        return $this->redirectToRoute('acecommerce_panier');
    }

    /**
     *
     * @Route("/deleteJSON", name="acecommerce_panier_produit_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     */
    public function deleteJSON(Request $request, CommandeProduitRepository $commandeProduitRepository)
    {
        $commandeProduitId = $request->request->get('commandeProduit');
        $commandeProduit = $commandeProduitRepository->find($commandeProduitId);

        if (!$commandeProduit) {

            //TODO : return infos concernant la suppression si erreur
            return new JsonResponse([
                "message"=>"Objet CommandeProduit non trouvé"
            ]);
        }

        $form = $this->createDeleteForm();
        $form->handleRequest($request);
        $this->panierManager->removeProduit($commandeProduit);
        return new JsonResponse([
            "message"=>"OK"
        ]);
    }

    /**
     *
     * @Route("/commentaire", name="acecommerce_panier_produit_commentaire")
     * @Method("POST")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     */
    public function commentaire(Request $request, CommandeProduitRepository $commandeProduitRepository)
    {
        $commandeProduitId = $request->request->get('commandeProduit');
        $commandeProduit = $commandeProduitRepository->find($commandeProduitId);

        if (!$commandeProduit) {
            $this->addFlash('danger', 'produit introuvable');

            return $this->redirectToRoute('acecommerce_panier');
        }

        $form = $this->createCommentaireForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->denyAccessUnlessGranted('edit', $commandeProduit, "Vous n'avez pas accès a cette commande.");
            $commentaire = $form->get('commentaire')->getData();

            $this->panierManager->commentaireProduit($commandeProduit, $commentaire);

            $this->addFlash('success', 'Le commentaire a bien été ajouté.');
        }

        return $this->redirectToRoute('acecommerce_panier');
    }

    /**
     * Vider le panier
     * @Route("/vider", name="acecommerce_panier_vider")
     * @Method("POST")
     * @Security("has_role('ROLE_ECOMMERCE_CLIENT')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function vider(Request $request)
    {
        $form = $this->createViderForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->panierManager->clean();
            $this->addFlash('success', 'La panier a bien été vidé.');
        }

        return $this->redirectToRoute('acecommerce_panier');
    }

    /**
     * @return FormInterface
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_panier_produit_delete'))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return FormInterface
     */
    private function createUpdateForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_panier_update'))
            ->setMethod('POST')
            ->getForm();
    }

    /**
     * @return FormInterface
     */
    private function createViderForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_panier_vider'))
            ->setMethod('POST')
            ->getForm();
    }

    /**
     * @return FormInterface
     */
    private function createCommentaireForm()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('acecommerce_panier_produit_commentaire'))
            ->setMethod('POST')
            ->getForm();
        $form->add(
            'commentaire',
            TextareaType::class,
            [
                'required' => false,
                'attr' => ['data-help' => 'Pas de beurre, pas trop de sauce...'],
            ]
        );

        return $form;
    }

}
