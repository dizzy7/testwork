<?php

namespace Service;

class FormService
{
    const CSRF_TOKEN_SESSION_ID = 'crsr_token';

    public function generateCsrfToken()
    {
        $session = \Application::$request->getSession();

        $token = uniqid('csrf_', true);
        $session->set(self::CSRF_TOKEN_SESSION_ID, $token);

        return $token;
    }

    public function validateOrderForm($formData)
    {
        $errors = [];

        if (!isset($formData['name']) || strlen($formData['name']) < 3) {
            $errors[] = 'Необходимо ввести имя';
        }

        if (!isset($formData['email']) || filter_var($formData['email'], FILTER_VALIDATE_EMAIL) !== $formData['email']) {
            $errors[] = 'Неверно введён email';
        }

        if (!isset($formData['phone']) ||  strlen($formData['phone']) < 7) {
            $errors[] = 'Неверно введен номер телефона';
        }

        if (!isset($formData['card']) ||  strlen($formData['card']) !== 16) { // +валидация на основе алгоритма Луна
            $errors[] = 'Неверно введен номер банковской карты';
        }

        if (!isset($formData['cvv']) ||  strlen($formData['cvv']) !== 3) {
            $errors[] = 'CVV-код введён неверно';
        }

        if (!isset($formData['csrfToken']) ||  !$this->isCsrfTokenValid($formData['csrfToken'])) {
            $errors[] = 'Ошибка проверки токена';
        }

        return $errors;
    }

    private function isCsrfTokenValid($csrfToken)
    {
        $session = \Application::$request->getSession();

        $sessionToken = $session->get(self::CSRF_TOKEN_SESSION_ID);
        $session->remove(self::CSRF_TOKEN_SESSION_ID);

        return $csrfToken && $sessionToken && $sessionToken === $csrfToken;
    }
}