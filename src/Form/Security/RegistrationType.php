<?php

namespace App\Form\Security;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/08/17
 * Time: 15:11
 */

use App\Entity\Security\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ]
            )
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Nom'],
                    'constraints' => new Length(['min' => 3]),
                ]
            )
            ->add(
                'prenom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Prénom'],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => ['placeholder' => 'email'],
                ]
            )
            ->add(
                'mobile',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Téléphone'],
                ]
            )
            ->add(
                'termsAccepted',
                CheckboxType::class,
                array(
                    'mapped' => false,
                    'constraints' => new IsTrue(),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }
}