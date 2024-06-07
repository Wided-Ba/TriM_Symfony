<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Age')
            ->add('Ntel')
            ->add('Email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('Password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Mot de passe',
                ],
            ])
            ->add('Adresse')
            ->add('genre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
