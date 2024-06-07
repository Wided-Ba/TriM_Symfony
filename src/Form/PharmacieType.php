<?php

namespace App\Form;

use App\Entity\Pharmacie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PharmacieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank(), // Le champ ne doit pas être vide
                ],
            ])
            ->add('ntel', null, [
                'constraints' => [
                    new NotBlank(), // Le champ ne doit pas être vide
                    new Regex(['pattern' => '/^\d{8}$/']), // Le numéro de téléphone doit avoir exactement 8 chiffres
                ],
            ])
            ->add('adresse', null, [
                'constraints' => [
                    new NotBlank(['message' => 'adresse ne doit pas être vide.']), // Le champ ne doit pas être vide
                    new Email(['message' => 'Le champ doit être une adresse email valide.']), // Le champ doit être une adresse email valide
                ],
            ])
            ->add('type', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ ne doit pas être vide']), // Le champ ne doit pas être vide
                ],
            ])
            ->add('loc', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le champ ne doit pas être vide']), // Le champ ne doit pas être vide
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pharmacie::class,
        ]);
    }
}
