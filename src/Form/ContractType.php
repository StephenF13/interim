<?php

namespace App\Form;

use App\Entity\Contract;
use App\Entity\Interim;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateStart', DateType::class, [
                'label'  => 'Date de début',
                'widget' => 'single_text',
                'html5'  => true,
                'format' => 'yyyy-MM-dd',
            ])
            ->add('dateEnd', DateType::class, [
                'label'  => 'Date de fin',
                'widget' => 'single_text',
                'html5'  => true,
                'format' => 'yyyy-MM-dd',
            ])
            ->add('status', ChoiceType::class, [
                'label'   => 'Statut',
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours'   => 'En cours',
                    'Terminé'    => 'Terminé',
                ],
            ])
            //  this part is for classic search
//            ->add('interim', EntityType::class, [
//                'class' => Interim::class,
//                'label' => 'Intérimaire',
//            ]);

            // this part is for ajax search
            ->add('interim', TextType::class, [
                'label' => 'Intérimaire',
                'attr'  => [
                    'placeholder' => 'Rechercher par le nom de l\'intérimaire',
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
