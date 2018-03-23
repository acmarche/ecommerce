<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 11/07/17
 * Time: 15:03
 */

namespace App\Form\Lunch;

use App\Entity\Lunch\Supplement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prix', MoneyType::class, [
                'attr' => ['data-help' => 'Prix HTVA']
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Supplement::class
        ));
    }

}