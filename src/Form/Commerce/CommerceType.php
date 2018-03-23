<?php

namespace App\Form\Commerce;

use App\Entity\Commerce\Commerce;
use App\Service\Bottin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CommerceType extends AbstractType
{
    private $bottin;

    public function __construct(Bottin $bottin)
    {
        $this->bottin = $bottin;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $requiredImage = $options['requiredImage'];

        try {
            $fiches = $this->bottin->getFichesForForm();
        } catch (\Exception $exception) {
            $fiches = ['Errer fiche : '.$exception->getMessage()=>0];
        }

        $builder
            ->add('nom')
            ->add(
                'numeroTva',
                TextType::class,
                ['required' => true]
            )
            ->add('iban')
            ->add(
                'tvaApplicable',
                PercentType::class,
                [
                    'scale' => 2,
                    'type' => 'integer',
                    'required' => false,
                    'attr' => ['data-help' => 'Si différente de 6%'],
                ]
            )
            ->add(
                'bottinId',
                ChoiceType::class,
                [
                    'choices' => $fiches,
                    'placeholder' => 'Sélectionnez une fiche du bottin',
                    'label' => 'Fiche du bottin',
                ]
            )
            ->add(
                'indisponible',
                CheckboxType::class,
                [
                    'required' => false,
                    'attr' => ['data-help' => 'Le commerce ne sera plus visible sur le site'],
                ]
            )
            ->add(
                'imageFile',
                VichFileType::class,
                [
                    'required' => $requiredImage,
                    'label' => 'image',
                ]
            )
            ->add(
                'sms_numero',
                TextType::class,
                [
                    'label' => 'Numéro de gsm pour recevoir les commandes',
                    'attr' => [
                        'help' => 'Format: 32476662615',
                    ],
                ]
            )
            ->add(
                'emailCommande',
                TextType::class,
                [
                    'label' => 'email pour les commandes',
                    'attr' => [
                        'help' => 'Mail sur lequel seront envoyé les commandes',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Commerce::class,
                'requiredImage' => true,
            )
        );

        $resolver->setRequired('requiredImage');
    }

}
