<?php

namespace App\Form;

use App\Entity\ChefLab;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChefLabType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('ntel')
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Email',
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Mot de passe',
                ],
            ])
            ->add('genre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChefLab::class,
        ]);
    }
}
