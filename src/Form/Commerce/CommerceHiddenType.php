<?php

namespace App\Form\Commerce;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\CommerceToNumberTransformer;

class CommerceHiddenType extends AbstractType {

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om) {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $transformer = new CommerceToNumberTransformer($this->om);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected commerce does not exist',
        ));
    }

    public function getParent() {
        return HiddenType::class;
    }
}
