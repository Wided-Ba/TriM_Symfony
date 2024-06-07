<?php

namespace App\Form;

use App\Entity\analyse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnalyseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom' , TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Sanguin' => 'Sanguin',
                    'Urinaire' => 'Urinaire',
                    'Fécal(e)' => 'Fécal(e)',
                    'Liquide céphalorachidien (LCR)' => 'Liquide céphalorachidien (LCR)',
                    'Salivaire' => 'Salivaire',
                    'Liquide synovial' => 'Liquide synovial',
                    'Liquide pleural' => 'Liquide pleural',
                    'Liquide amniotique' => 'Liquide amniotique',
                    'Liquide gastrique' => 'Liquide gastrique',
                ],
                'expanded' => false, // Afficher en tant que menu déroulant (choix unique)
                'multiple' => false, // Permettre un seul choix
                'placeholder' => 'Choisir un type', // Texte à afficher comme premier élément vide
            ])
            ->add('outillage', TextType::class)
            ->add('conseils', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => analyse::class,
        ]);
    }
}
