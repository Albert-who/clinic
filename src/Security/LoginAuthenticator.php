<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    // Метод authenticate() вызывается при попытке аутентификации пользователя
    public function authenticate(Request $request): Passport
    {
        // Получаем имя пользователя из запроса
        $username = $request->request->get('username', '');

        // Сохраняем имя пользователя в сессии
        $request->getSession()->set(Security::LAST_USERNAME, $username);

        // Создаем паспорт, содержащий данные для аутентификации
        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    // Метод onAuthenticationSuccess() вызывается при успешной аутентификации пользователя
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Если имеется сохраненный URL для перенаправления, перенаправляем пользователя на этот URL
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Проверяем, является ли пользователь врачом
        if ($token->getUser()->IsDoctor()) {
            // Если пользователь врач, перенаправляем его на страницу врача
            return new RedirectResponse($this->urlGenerator->generate('app_doctor'));
        } else {
            // Если пользователь не врач, перенаправляем его на страницу обычного пользователя
            return new RedirectResponse($this->urlGenerator->generate('app_user'));
        }
    }

    // Метод getLoginUrl() возвращает URL для страницы входа в систему
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
