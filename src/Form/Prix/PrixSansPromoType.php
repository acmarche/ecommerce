<?php

namespace App\Form\Prix;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PrixSansPromoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('promo_htva');
    }

    public function getParent()
    {
        return PrixType::class;
    }
}
