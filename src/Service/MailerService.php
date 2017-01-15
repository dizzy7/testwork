<?php

namespace Service;

use Service\Templating;

class MailerService
{
    private static $instance;

    private $mailer;

    protected function __construct()
    {
        $transport = new \Swift_SmtpTransport(
            \Application::$config['mailer']['host'],
            \Application::$config['mailer']['port']
        );
        $this->mailer = new \Swift_Mailer($transport);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new MailerService();
        }

        return self::$instance;
    }

    /**
     * отправку сообщений лучше вынести в отдельный сервис через сервер очередей/redis/etc
     */
    public function send($email, $subject, $body)
    {
        /** @var \Swift_Message $message */
        $message = $this->mailer->createMessage();

        $message->setSubject($subject);
        $message->setTo($email);
        $message->setFrom(\Application::$config['mailer']['from']);

        $message->setBody($body);

        $this->mailer->send($message);
    }

}