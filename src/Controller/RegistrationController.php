<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        // Создаем новый экземпляр сущности User
        $user = new User();
        
        // Создаем форму регистрации с использованием RegistrationFormType и передаем ей сущность User
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        // Обрабатываем запрос
        $form->handleRequest($request);

        // Проверяем, была ли форма отправлена и валидна
        if ($form->isSubmitted() && $form->isValid()) {
            // Хэшируем введенный пароль и устанавливаем его в сущность User
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

             // Устанавливаем значение isDoctor на основе значения из формы
             $user->setIsDoctor($form->get('isDoctor')->getData());

            // Добавляем сущность User в Unit of Work для сохранения
            $entityManager->persist($user);
            
            // Сохраняем изменения в базе данных
            $entityManager->flush();

            // Аутентифицируем пользователя и перенаправляем на соответствующую страницу
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        // Отображаем шаблон страницы регистрации и передаем форму в представление
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
