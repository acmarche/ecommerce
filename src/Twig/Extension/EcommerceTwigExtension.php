<?php

namespace App\Twig\Extension;

use App\Entity\Categorie\CategorieImage;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CategoryInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Produit\ProduitImage;
use App\Helper\FileHelper;
use App\Service\TvaService;

class EcommerceTwigExtension extends \Twig_Extension
{
    protected $tvaService;
    protected $fileHelper;

    public function __construct(TvaService $tvaService, FileHelper $fileHelper)
    {
        $this->tvaService = $tvaService;
        $this->fileHelper = $fileHelper;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('prixTvac', array($this, 'tvacFilter')),
            new \Twig_SimpleFilter('prixPromoTvac', array($this, 'promoTvacFilter')),
            new \Twig_SimpleFilter('attributPrixTvac', array($this, 'attributTvacFilter')),
            new \Twig_SimpleFilter('attributPrixPromoTvac', array($this, 'attributPromoTvacFilter')),
            new \Twig_SimpleFilter('prixNice', array($this, 'prixNiceFilter')),
            new \Twig_SimpleFilter('etapeCheck', array($this, 'etapeFilter')),
            new \Twig_SimpleFilter('horaireNice', array($this, 'horaireFilter')),
        );
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('resolveImageCategorie', array($this, 'resolveImageCategorie')),
            new \Twig_SimpleFunction('resolveImageProduit', array($this, 'resolveImageProduit')),
        ];
    }

    public function tvacFilter(ProduitInterface $produit)
    {
        return $this->tvaService->getPrixProduitTvac($produit);
    }

    public function promoTvacFilter(ProduitInterface $produit)
    {
        return $this->tvaService->getPrixPromoTvac($produit);
    }

    public function attributTvacFilter(AttributInterface $attribut, ProduitInterface $produit)
    {
        $tva = $this->tvaService->getTvaApplicable($produit);
        return $this->tvaService->getPrixTvac($attribut->getPrixHtva(), $tva);
    }

    public function attributPromoTvacFilter(AttributInterface $attribut, ProduitInterface $produit)
    {
        $tva = $this->tvaService->getTvaApplicable($produit);
        return $this->tvaService->getPrixTvac($attribut->getPrixPromoHtva(),$tva);
    }

    public function prixNiceFilter(float $number)
    {
        $price = number_format($number, 2, ",", ".");
        $price = $price.'€';

        return $price;
    }

    public function etapeFilter(bool $boolean)
    {
        if ($boolean == true) {
            return 'success';
        }

        return 'warning';
    }

    public function resolveImageCategorie(CategoryInterface $category, CategorieImage $categorieImage)
    {
        return $this->fileHelper->getCategorieDownloadDirectory($category).$categorieImage->getName();
    }

    public function resolveImageProduit(ProduitInterface $produit, ProduitImage $produitImage)
    {
        return $this->fileHelper->getProduitDownloadDirectory($produit).$produitImage->getName();
    }

    /**
     *"id": "2826"
     *"day": "1"
     *"media_path": null
     *"is_open_at_lunch": "1"
     *"is_rdv": "0"
     *"morning_start": "07:00:00"
     *"morning_end": null
     *"noon_start": null
     *"noon_end": "18:30:00"
     *"created": "2016-05-04 09:35:50"
     *"updated": "2017-07-18 09:46:32"
     *"fiche_id": "356"
     *"is_closed": "0"
     */
    public function horaireFilter($horaire)
    {
        if ($horaire->is_closed) {
            return 'Fermé';
        }
        $txt = '';

        if ($horaire->morning_start) {
            $txt .= $this->removeSeconde($horaire->morning_start);
        }

        if ($horaire->morning_end) {
            $txt .= '-'.$this->removeSeconde($horaire->morning_end);
        }

        if ($horaire->noon_start) {
            if ($horaire->morning_end) {
                $txt .= ' | ';
            }
            $txt .= $this->removeSeconde($horaire->noon_start);
        }

        if ($horaire->noon_end) {
            $txt .= '-'.$this->removeSeconde($horaire->noon_end);
        }

        if ($horaire->is_rdv) {
            $txt .= ' sur rdv';
        }

        return $txt;
    }

    private function removeSeconde($heure)
    {
        list($heure, $minute) = explode(":", $heure);

        return $heure.':'.$minute;
    }

}
