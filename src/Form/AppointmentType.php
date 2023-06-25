<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
                'label' => 'Выберите услугу',
                'placeholder' => 'Выберите вариант',
            ])
            ->add('date', DateType::class, [
                'label' => 'Выберите дату',
                'placeholder' => 'Выберите вариант',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',  
            ])
            // ->add('date', ChoiceType::class, [
            //     'label' => 'Выберите дату',
            //     'placeholder' => 'Выберите вариант',
            //     'choices' => array_flip($options['available_dates']), // Используем доступные даты в качестве вариантов
            //     'choice_translation_domain' => false, // Отключаем перевод вариантов
            // ])
            ->add('DuserId', HiddenType::class, [ // Добавляем поле DuserId с типом HiddenType
                'mapped' => false, // Устанавливаем mapped в false, чтобы поле не было привязано к сущности Appointment
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Appointment',
            // 'available_dates' => [], 
        ]);
    }
}
