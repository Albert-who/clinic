<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Добавляем поле "username" в форму
        $builder->add('username');
        
         // Добавляем поле "Вы врач?" в форму с помощью CheckboxType
         $builder->add('isDoctor', CheckboxType::class, [
            'required' => false, // Поле не обязательное для заполнения
        ]);

        // Добавляем поле "plainPassword" в форму с определенными настройками
        $builder->add('plainPassword', PasswordType::class, [
            // Параметр 'mapped' указывает, что это поле не должно быть привязано к свойству сущности User
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                // Добавляем валидацию для пароля
                new NotBlank([
                    'message' => 'Пожалуйста, введите пароль',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Ваш пароль должен содержать не менее {{ limit }} символов',
                    // Максимальная длина, разрешенная Symfony из соображений безопасности
                    'max' => 4096,
                ]),
            ],
        ]);
        
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Указываем класс сущности, с которой связана форма
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
