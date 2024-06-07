<?php

namespace App\Form;

use App\Entity\maladie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaladieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('symptome')
            ->add('description')
            ->add('type')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Maladie virale' => 'virale',
                    'Maladie bactérienne' => 'bacterienne',
                    'Maladie parasitaire' => 'parasitaire',
                    'Maladie fongique' => 'fongique',
                    'Maladie génétique' => 'genetique',
                    'Maladie auto-immune' => 'auto-immune',
                    'Maladie cardiovasculaire' => 'cardiovasculaire',
                    'Maladie neurologique' => 'neurologique',
                    'Maladie respiratoire' => 'respiratoire',
                    'Maladie endocrinienne' => 'endocrinienne',
                ],

                'expanded' => false, // Afficher en tant que menu déroulant (choix unique)
                'multiple' => false, // Permettre un seul choix
                'placeholder' => 'Choisir un type', // Texte à afficher comme premier élément vide
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => maladie::class,
        ]);
    }
}
