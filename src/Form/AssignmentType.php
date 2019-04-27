<?php

namespace App\Form;

use App\Entity\Assignment;
use App\Entity\Contract;
use App\Entity\Interim;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('interim', EntityType::class, [
                'class' => Interim::class,
                'label' => 'Intérimaire',
            ])
            ->add('contract', EntityType::class, [
                'class' => Contract::class,
                'label' => 'Contrat',
            ])
            ->add('status', ChoiceType::class, [
                'label'   => 'Statut',
                'choices' => [
                    'Actif'    => 'Actif',
                    'Supprimé' => 'Supprimé',
                ],
            ])
            ->add('rating', ChoiceType::class, [
                'label'        => 'Note',
                'choices'      => range(1, 10),
                'choice_label' => function ($choice) {
                    return $choice;
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Assignment::class,
        ]);
    }
}
