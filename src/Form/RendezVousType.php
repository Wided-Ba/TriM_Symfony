<?php

namespace App\Form;

use App\Entity\medecin;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Choisir une date :',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'min' => (new \DateTime())->format('Y-m-d'), // Définit la date minimale à aujourd'hui
                ],
            ])
            ->add('heure', TimeType::class, [
                'label' => 'Choisir une heure :',
                'input' => 'datetime',
                'widget' => 'choice',
                'attr' => [
                    'class' => 'form-control form-control-user',
                ],
                'hours' => range(8, 20),
            ])


            ->add('motif', ChoiceType::class, [
                'label' => 'Choisir un motif',
                'attr' => [

                    'class' => 'form-control form-control-user',
                    'onchange' => 'updateLabel(this)',
                ],
                'choices' => [
                    'Consultation' => 'Consultation',
                    'Examen' => 'Examen',
                    'Suivi' => 'Suivi',
                ],
                'placeholder' => 'Motif', // Laissez le placeholder vide pour que le label soit affiché
            ])

            ->add('idMedecins', EntityType::class, [
                'class'=> medecin::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
