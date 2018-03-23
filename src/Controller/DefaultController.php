<?php

namespace App\Controller;

use App\Entity\Categorie\Categorie;
use App\Form\Client\ContactGeneralType;
use App\Helper\FileHelper;
use App\Entity\Produit\Produit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    protected $commerce;

    /**
     * @Route("/", name="acecommerce_home")
     *
     */
    public function index(FileHelper $fileHelper)
    {
        $em = $this->getDoctrine()->getManager();
        $lunch = $em->getRepository(Categorie::class)->findOneBy(['nom' => 'Lunch']);
        $ecommerce = $em->getRepository(Categorie::class)->findOneBy(['nom' => 'Ecommerce']);

        $produits = $em->getRepository(Produit::class)->search([]);

        return $this->render(
            'default/index.html.twig',
            [
                'lunch' => $lunch,
                'ecommerce' => $ecommerce,
                'produits' => $produits,
            ]
        );
    }

    /**
     *
     * @Route("/about", name="acecommerce_about")
     * @Method("GET")
     * @Template()
     */
    public function about()
    {
        return [

        ];
    }

    /**
     *
     * @Route("/contact", name="acecommerce_contact")
     * @Method("GET")
     * @Template()
     */
    public function contact()
    {
        $form = $this->createForm(ContactGeneralType::class);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     *
     * @Route("/doc/condition", name="acecommerce_condition")
     * @Method("GET")
     * @Template()
     */
    public function condition()
    {
        return [

        ];
    }

    /**
     *
     * @Route("/doc/livraison", name="acecommerce_livraison_doc")
     * @Method("GET")
     * @Template()
     */
    public function livraison()
    {
        return [

        ];
    }

    /**
     *
     * @Route("/doc/dgpr", name="acecommerce_dgpr")
     * @Method("GET")
     * @Template()
     */
    public function dgpr()
    {
        return [

        ];
    }

    /**
     *
     * @Route("/doc/paiement", name="acecommerce_paiement_doc")
     * @Method("GET")
     * @Template()
     */
    public function paiement()
    {
        return [

        ];
    }

    /**
     *
     * @Route("/react", name="acecommerce_react")
     * @Method("GET")
     * @Template()
     */
    public function react()
    {
        return [

        ];
    }


}
