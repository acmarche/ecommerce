<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 19/09/16
 * Time: 15:09
 */


namespace App\Helper;

use App\Entity\Categorie\Categorie;
use App\Entity\InterfaceDef\CategoryInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class FileHelper
{
    private $container;
    private $separator;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->separator = DIRECTORY_SEPARATOR;
    }

    public function uploadFile($directory, UploadedFile $file, $fileName)
    {
        $result = $file->move($directory, $fileName);

        return $result;
    }

    public function deleteOneDoc($directory, $filename)
    {
        $file = $directory.$this->separator.$filename;

        $fs = new Filesystem();
        $fs->remove($file);
    }

    public function deleteAllDocs($directory)
    {
        $fs = new Filesystem();
        $fs->remove($directory);
    }

    /**
     * @todo add id : .$produit->getId().$this->separator
     * @param ProduitInterface $produit
     * @return String
     */
    public function getProduitDownloadDirectory(ProduitInterface $produit): String
    {
        return $this->container->getParameter(
                'acmarche_lunch_download_produit_directory'
            ).$this->separator;
    }

    /**
     * @todo add id : .$produit->getId().$this->separator
     * @param ProduitInterface $produit
     * @return String
     */
    public function getProduitUploadDirectory(ProduitInterface $produit): String
    {
        return $this->container->getParameter(
                'acmarche_lunch_upload_produit_directory'
            ).$this->separator;
    }

    public function getCategorieDownloadDirectory(CategoryInterface $categorie): String
    {
        return $this->container->getParameter(
                'acmarche_lunch_download_categorie_directory'
            ).$this->separator;
    }

    public function getCategorieUploadDirectory(Categorie $categorie): String
    {
        return $this->container->getParameter(
                'acmarche_lunch_upload_categorie_directory'
            ).$this->separator;
    }
}