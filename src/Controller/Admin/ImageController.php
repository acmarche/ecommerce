<?php

namespace App\Controller\Admin;

use App\Entity\Categorie\Categorie;
use App\Entity\Produit\ProduitImage;
use App\Entity\Categorie\CategorieImage;
use App\Entity\Produit\Produit;
use App\Repository\Categorie\CategorieImageRepository;
use App\Repository\Produit\ProduitImageRepository;
use App\Helper\FileHelper;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * image controller.
 *
 * @Route("/admin/image")
 */
class ImageController extends Controller
{
    /**
     * Displays a form to create a new image entity.
     *
     * @Route("/categorie/new/{id}", name="categorie_images_edit")
     * @Method("GET")
     *
     * @Template()
     */
    public function imageCategorie(
        Categorie $categorie,
        CategorieImageRepository $imageCategorieRepository
    ) {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_image_upload', array('id' => $categorie->getId())))
            ->setMethod('POST')
            ->getForm();

        $images = $imageCategorieRepository->findBy(['categorie' => $categorie]);
        $deleteForm = $this->createDeleteCategorieForm($categorie->getId());

        return array(
            'images' => $images,
            'form_delete' => $deleteForm->createView(),
            'categorie' => $categorie,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/categorie/upload/{id}", name="categorie_image_upload")
     * @Method("POST")
     *
     */
    public function uploadCategorie(Request $request, Categorie $categorie, FileHelper $fileHelper): Response
    {
        if ($request->isXmlHttpRequest()) {

            $file = $request->files->get('file');

            if ($file instanceof UploadedFile) {

                $fileName = md5(uniqid()).'.'.$file->guessClientExtension();
                $directory = $fileHelper->getCategorieUploadDirectory($categorie);

                try {
                    $fileHelper->uploadFile($directory, $file, $fileName);
                    $imageProduit = new CategorieImage($categorie, $fileName);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($imageProduit);
                    $em->flush();
                } catch (FileException $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

            return new Response('okid');
        }

        return new Response('ko');
    }

    /**
     * Displays a form to create a new image entity.
     *
     * @Route("/produit/new/{id}", name="produit_images_edit")
     * @Method("GET")
     * @Security("is_granted('edit', produit)")
     * @Template()
     */
    public function imageProduit(
        Produit $produit,
        ProduitImageRepository $imageProduitRepository
    ) {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_image_upload', array('id' => $produit->getId())))
            ->setMethod('POST')
            ->getForm();

        $images = $imageProduitRepository->findBy(['produit' => $produit]);
        $deleteForm = $this->createDeleteProduitForm($produit->getId());

        return array(
            'images' => $images,
            'form_delete' => $deleteForm->createView(),
            'produit' => $produit,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/produit/upload/{id}", name="produit_image_upload")
     * @Method("POST")
     * @Security("is_granted('edit', produit)")
     */
    public function uploadProduit(
        Request $request,
        Produit $produit,
        FileHelper $fileHelper
    ): Response {
        if ($request->isXmlHttpRequest()) {

            $file = $request->files->get('file');

            if ($file instanceof UploadedFile) {

                $fileName = md5(uniqid()).'.'.$file->guessClientExtension();
                $directory = $fileHelper->getProduitUploadDirectory($produit);

                try {
                    $fileHelper->uploadFile($directory, $file, $fileName);
                    $imageProduit = new ProduitImage($produit, $fileName);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($imageProduit);
                    $em->flush();

                } catch (FileException $error) {
                    $this->addFlash('warning', $error->getMessage());
                }
            }

            return new Response('okid');
        }

        return new Response('ko');
    }

    /**
     * Deletes a image entity.
     *
     * @Route("/delete/imageproduit/{id}", name="produit_image_delete")
     * @Method("DELETE")
     * @Security("is_granted('edit', produit)")
     *
     */
    public function deleteProduit(
        Request $request,
        Produit $produit,
        FileHelper $fileHelper,
        ProduitImageRepository $imageProduitRepository
    ) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createDeleteProduitForm($produit->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $request->get('imgs', false);

            if (!$files) {
                $this->addFlash('warning', "Vous n'avez sélectionnez aucune photo");

                return $this->redirectToRoute('produit_images_edit', array('id' => $produit->getId()));
            }

            $directory = $fileHelper->getProduitUploadDirectory($produit);

            foreach ($files as $fileId) {

                $imageProduit = $imageProduitRepository->find($fileId);
                if (!$imageProduit) {
                    $this->addFlash('warning', "L'image n'a pas pu être trouvée. ");

                    return $this->redirectToRoute('produit_images_edit', array('id' => $produit->getId()));
                }

                $filename = $imageProduit->getName();
                try {
                    $fileHelper->deleteOneDoc($directory, $filename);
                    $this->addFlash('success', "L'image $filename a bien été supprimée");
                    $em->remove($imageProduit);
                    $em->flush();
                } catch (FileException $e) {
                    $this->addFlash('warning', "L'image  $filename n'a pas pu être supprimée. ");
                }
            }
        }

        return $this->redirectToRoute('produit_images_edit', array('id' => $produit->getId()));
    }

    /**
     * @param $id
     * @return FormInterface
     */
    private function createDeleteProduitForm($id): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_image_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add(
                'submit',
                SubmitType::class,
                array('label' => 'Supprimer les images sélectionnées', 'attr' => array('class' => 'btn-danger btn-xs'))
            )
            ->getForm();
    }

    /**
     * Deletes a image entity.
     *
     * @Route("/delete/imagecategorie/{id}", name="categorie_image_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ECOMMERCE_ADMIN')")
     *
     */
    public function deleteCategorie(
        Request $request,
        Categorie $categorie,
        FileHelper $fileHelper,
        CategorieImageRepository $imageCategorieRepository
    ) {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createDeleteCategorieForm($categorie->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $request->get('imgs', false);

            if (!$files) {
                $this->addFlash('warning', "Vous n'avez sélectionnez aucune photo");

                return $this->redirectToRoute('categorie_images_edit', array('id' => $categorie->getId()));
            }

            $directory = $fileHelper->getCategorieUploadDirectory($categorie);

            foreach ($files as $fileId) {

                $imageCategorie = $imageCategorieRepository->find($fileId);
                if (!$imageCategorie) {
                    $this->addFlash('warning', "L'image n'a pas pu être trouvée. ");

                    return $this->redirectToRoute('categorie_images_edit', array('id' => $categorie->getId()));
                }

                $filename = $imageCategorie->getName();
                try {
                    $fileHelper->deleteOneDoc($directory, $filename);
                    $this->addFlash('success', "L'image $filename a bien été supprimée");
                    $em->remove($imageCategorie);
                    $em->flush();
                } catch (FileException $e) {
                    $this->addFlash('warning', "L'image  $filename n'a pas pu être supprimée. ");
                }
            }
        }

        return $this->redirectToRoute('categorie_images_edit', array('id' => $categorie->getId()));
    }

    /**
     * @param $id
     * @return FormInterface
     */
    private function createDeleteCategorieForm($id): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorie_image_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add(
                'submit',
                SubmitType::class,
                array('label' => 'Supprimer les images sélectionnées', 'attr' => array('class' => 'btn-danger btn-xs'))
            )
            ->getForm();
    }

}
