<?php

namespace App\Form;

use App\Entity\analyse;
use App\Entity\lab;
use App\Entity\Ordonnance;
use App\Entity\pharmacie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdonnanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description',TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Labo' => 'Labo',
                    'Pharamcie' => 'Pharamcie',
                ],
                'expanded' => false,
                'multiple' => false,
                'placeholder' => 'Choisir un type',
            ])
            ->add('idPharmacies', EntityType::class, [
                'class' => Pharmacie::class,
                'choice_label' => 'nom',
            ])
            ->add('idLabs', EntityType::class, [
                'class' => Lab::class,
                'choice_label' => 'nom',
            ])
            ->add('analyse_list', EntityType::class, [
                'class' => analyse::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ordonnance::class,
        ]);
    }
}
