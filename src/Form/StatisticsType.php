<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatisticsType extends AbstractType
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
            ->add('save', SubmitType::class, [
                'label' => 'Télécharger',
            ]);

    }


}
