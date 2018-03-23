<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/12/17
 * Time: 14:20
 */

namespace App\Controller\Admin;


use App\Entity\Produit\ProduitImage;
use App\Entity\Produit\Produit;
use App\Repository\Produit\ProduitImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TrierController
 * @package App\Controller\admin
 * @Route("/admin/trier")
 */
class TrierController extends AbstractController
{
    /**
     * trier les images
     *
     * @Route("/{id}", name="acecommerce_admin_image_trier")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function trier(
        Request $request,
        Produit $produit,
        ProduitImageRepository $imageProduitRepository
    ) {
        $em = $this->getDoctrine()->getManager();
        $isAjax = $request->isXmlHttpRequest();
        $images = $imageProduitRepository->getImages($produit);

        if ($isAjax) {
            $images = $request->request->get("images");
            if (is_array($images)) {
                foreach ($images as $position => $imageId) {
                    $imageProduit = $em->getRepository(ProduitImage::class)->find($imageId);
                    if ($imageProduit) {
                        $imageProduit->setPosition($position);
                        $em->persist($imageProduit);
                    }
                }
                $em->flush();

                return new Response('<div class="alert alert-success">Tri enregistré</div>');
            }

            return new Response('<div class="alert alert-error">Erreur</div>');
        }

        return [
            'produit' => $produit,
            'images' => $images,
        ];
    }
}