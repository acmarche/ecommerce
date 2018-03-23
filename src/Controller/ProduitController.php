<?php

namespace App\Controller;

use App\Checker\ProduitChecker;
use App\Entity\Produit\Produit;
use App\Form\Commande\CommandeProduitType;
use App\Form\Search\RechercheProduit;
use App\Manager\CommandeProduitManager;
use App\Repository\Attribut\ProduitListingAttributRepository;
use App\Repository\Produit\ProduitImageRepository;
use App\Repository\Produit\ProduitRepository;
use App\Service\QuantiteService;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class ProduitController
 * @package App\Controller
 * @Route("/produits")
 */
class ProduitController extends AbstractController
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="acecommerce_produit_index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function index(
        Request $request,
        SessionInterface $session,
        ProduitRepository $produitRepository
    ) {
        $key = "ecommerce_search";
        $args = ['rand' => true];

        if ($session->has($key)) {
            $args = unserialize($session->get($key));
        }

        if ($motclef = $request->get('motclef')) {
            $args['motclef'] = $motclef;
        }

        $form = $this->createForm(
            RechercheProduit::class,
            $args
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dataForm = $form->getData();
            $args['motclef'] = $dataForm['motclef'];
            $args['commerce'] = $dataForm['commerce'];
        }

        $session->set($key, serialize($args));
        $produits = $produitRepository->search($args);

        return [
            'produits' => $produits,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="acecommerce_produit_show")
     * @Method("GET")
     *
     * @Template()
     */
    public function show(
        Produit $produit,
        ProduitChecker $produitChecker,
        CommandeProduitManager $commandeProduitManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        ProduitImageRepository $imageProduitRepository,
        ProduitRepository $produitRepository,
        ProduitListingAttributRepository $produitListingAttributRepository,
        QuantiteService $quantiteService
    ) {
        if (!$produitChecker->canDisplayOnsite($produit)) {
            throw $this->createNotFoundException('Ce produit n\'est pas disponible');
        }

        $user = $this->getUser();

        try {
            $commandeProduit = $commandeProduitManager->getOrInitCommandeProduit($produit, $user);
        } catch (NonUniqueResultException $e) {
            $this->addFlash('error', $e->getMessage());
            $this->redirectToRoute('acecommerce_produit_index');
        }

        $form = $this->createForm(CommandeProduitType::class, $commandeProduit);

        $quantiteDansPanier = $quantiteService->countProduitInPanier($produit, $user);

        $token = $csrfTokenManager->refreshToken($produit);

        $images = $imageProduitRepository->findBy(['produit' => $produit]);

        $suggestions = $produitRepository->getSuggestions();
        $suggestionsNonLunch = $produitRepository->getSuggestionsLunch();

        $productListingsAttributs = $produitListingAttributRepository->findBy(['produit' => $produit]);

        return [
            'token' => $token,
            'form' => $form->createView(),
            'produit' => $produit,
            'suggestions' => $suggestions,
            'suggestionsNonLunch' => $suggestionsNonLunch,
            'images' => $images,
            'productListingsAttributs' => $productListingsAttributs,
            'commandeProduit' => $commandeProduit,
            'quantiteDansPanier' => $quantiteDansPanier,
        ];
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/box/{id}", name="acecommerce_produit_box")
     * @Method("GET")
     *
     */
    public function box(
        Produit $produit,
        ProduitChecker $produitChecker,
        ProduitImageRepository $imageProduitRepository
    ) {
        if (!$produitChecker->canDisplayOnsite($produit)) {
            throw $this->createNotFoundException('The Produit does not exist');
        }

        $images = $imageProduitRepository->findBy(['produit' => $produit]);

        return new Response(
            $this->renderView(
                'produit/_carousel.html.twig',
                [
                    'produit' => $produit,
                    'images' => $images,
                ]
            )
        );
    }
}

