<?php

namespace App\Controller\Admin;

use App\Entity\Attribut\ListingAttributs;
use App\Entity\Commerce\Commerce;
use App\Form\Attribut\ListingAttributsType;
use App\Manager\InterfaceDef\AttributManagerInterface;
use App\Manager\InterfaceDef\ListingAttributManagerInterface;
use App\Repository\Attribut\ListingAttributsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ListingController
 * @package App\Controller
 * @Route("/admin/listing")
 * @Security("has_role('ROLE_ECOMMERCE_COMMERCE')")
 */
class ListingController extends Controller
{
    private $listingAttributManager;
    private $attributManager;
    private $listingAttributsRepository;

    public function __construct(
        ListingAttributManagerInterface $listingAttributManager,
        AttributManagerInterface $attributManager,
        ListingAttributsRepository $listingAttributsRepository
    ) {
        $this->listingAttributManager = $listingAttributManager;
        $this->attributManager = $attributManager;
        $this->listingAttributsRepository = $listingAttributsRepository;
    }

    /**
     * @Route("/", name="acecommerce_admin_listing")
     */
    public function index()
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_ECOMMERCE_ADMIN')) {
            $listing = $this->listingAttributsRepository->findAll();
        } else {
            $listing = $this->listingAttributsRepository->getOwnedByUser($user);
        }

        return $this->render(
            'admin/listing/index.html.twig',
            [
                'listing' => $listing,
            ]
        );
    }

    /**
     * @Route("/new/{id}", name="acecommerce_admin_listing_new")
     * @Security("is_granted('addlisting', commerce)")
     */
    public function new(Request $request, Commerce $commerce)
    {
        $listing = $this->listingAttributManager->create($commerce);
        $attribut = $this->attributManager->init($listing);

        $listing->getAttributs()->add($attribut);

        $formListing = $this->createForm(ListingAttributsType::class, $listing);

        $formListing->handleRequest($request);

        if ($formListing->isSubmitted() && $formListing->isValid()) {

            $this->listingAttributManager->insert($listing);
            $this->addFlash('success', 'Le listing a bien été ajouté');

            return $this->redirectToRoute('acecommerce_admin_listing');
        }

        return $this->render(
            'admin/listing/new.html.twig',
            [
                'form_listing' => $formListing->createView(),
                'commerce' => $commerce,
            ]
        );
    }

    /**
     * Finds and displays a entity.
     *
     * @Route("/{id}", name="acecommerce_admin_listing_show")
     * @Method("GET")
     * @Security("is_granted('show', listingAttributs)")
     */
    public function show(ListingAttributs $listingAttributs)
    {
        //$deleteForm = $this->createDeleteForm($listingAttributs);
        return $this->render(
            'admin/listing/show.html.twig',
            [
                'listingAttributs' => $listingAttributs,
            ]
        );
    }

    /**
     * Finds and displays a  entity.
     *
     * @Route("/edit/{id}", name="acecommerce_admin_listing_edit")
     * @Method({"GET","POST"})
     * @Security("is_granted('edit', listingAttributs)")
     */
    public function edit(Request $request, ListingAttributs $listingAttributs)
    {
        $originalAttributs = $this->listingAttributManager->getOriginal($listingAttributs);
        $formListing = $this->createForm(ListingAttributsType::class, $listingAttributs);

        $formListing->handleRequest($request);

        if ($formListing->isSubmitted() && $formListing->isValid()) {

            $this->listingAttributManager->handlerEdit($listingAttributs, $originalAttributs);
            $this->listingAttributManager->update($listingAttributs);

            $this->addFlash('success', 'Le listing a bien été modifié');

            return $this->redirectToRoute('acecommerce_admin_listing_show', ['id' => $listingAttributs->getId()]);
        }

        return $this->render(
            'admin/listing/edit.html.twig',
            [
                'listingAttributs' => $listingAttributs,
                'form_listing' => $formListing->createView(),
            ]
        );
    }


}
