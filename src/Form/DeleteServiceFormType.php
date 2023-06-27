<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DeleteServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('service', EntityType::class, [
            'class' => Service::class,
            'choice_label' => 'name',
            'label' => 'Выберите услугу для удаления',
            // 'placeholder' => 'Выберите услугу',
            
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
