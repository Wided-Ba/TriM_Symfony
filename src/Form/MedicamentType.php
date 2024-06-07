<?php

namespace App\Form;

use App\Entity\Medicament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne doit pas être vide.']),
                ],
            ])
            ->add('dateprod', DateType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La date de production ne doit pas être vide.']),
                    // Vous pouvez ajouter d'autres contraintes si nécessaire
                ],
            ])
            ->add('dateexp', DateType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'expiration ne doit pas être vide.']),
                    // Vous pouvez ajouter d'autres contraintes si nécessaire
                ],
            ])
            ->add('prix', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prix ne doit pas être vide.']),
                    new PositiveOrZero(['message' => 'Le prix doit être un nombre positif ou zéro.']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La description ne doit pas être vide.']),
                ],
            ])
            ->add('disponibilite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}
