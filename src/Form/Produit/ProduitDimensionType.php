<?php

namespace App\Form\Produit;

use App\Entity\Produit\ProduitDimension;
use App\Service\ParamsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitDimensionType extends AbstractType
{
    private $paramsUtil;

    /**
     * ProduitDimensionType constructor.
     * @param $paramsUtil
     */
    public function __construct(ParamsService $paramsUtil)
    {
        $this->paramsUtil = $paramsUtil;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $unites = $this->paramsUtil->getUnitePoids();

        $builder
            ->add(
                'hauteur',
                IntegerType::class,
                [
                    'required' => false,
                    'attr'=>['data-help'=>'En cm']
                ]
            )
            ->add('largeur',
                IntegerType::class,
                [
                    'required' => false,
                    'attr'=>['data-help'=>'En cm']
                ])
            ->add('longueur',
                IntegerType::class,
                [
                    'required' => false,
                    'attr'=>['data-help'=>'En cm']
                ])
            ->add('poids')
            ->add(
                'poidsUnite',
                ChoiceType::class,
                [
                    'choices' => $unites,
                    'required' => false,
                ]
            )
            ->add('remarque');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => ProduitDimension::class,
            )
        );
    }

}
