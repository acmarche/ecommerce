<?php

namespace App\Form\Commerce;

use App\Entity\Commerce\Commerce;
use App\Repository\Commerce\CommerceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommerceSelectType extends AbstractType
{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected commerce does not exist',
        ));

        $resolver->setDefaults(array(
            'required' => true,
            'label' => 'dommerce',
            'class' => Commerce::class,
            'query_builder' => function (CommerceRepository $cr) {
                return $cr->getForList();
            },
            'placeholder' => 'Choisissez un commerce',
        ));
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
