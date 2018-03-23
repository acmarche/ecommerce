<?php

namespace App\Form\Client;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactGeneralType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'label' => 'Votre nom',
                    'attr' => [],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Votre email',
                    'attr' => [],
                    'constraints' => array(new Email()),
                ]
            )
            ->add(
                'commentaire',
                TextareaType::class,
                [
                    'attr' => ['placeholder' => 'Commentaire', 'rows' => 3],
                    'constraints' => array(new Length(array('min' => 10))),
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Envoyer',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

}
