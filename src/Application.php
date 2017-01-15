<?php

use Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;

class Application
{
    /** @var Request */
    public static $request;
    /** @var array */
    public static $config;

    private static $debug;

    public static function init(array $config, $debug = false)
    {
        self::$request = Request::createFromGlobals();
        self::$config = $config;
        self::$debug = $debug;

        // Стандартный механизм сессий php. для повышения производительности - вынести сессии в memcache/redis
        $session = new Session();
        self::$request->setSession($session);
    }

    /**
     * @return Response
     */
    public static function handleRequest()
    {
        /** @noinspection MoreThanThreeArgumentsInspection */
        set_error_handler(
            function ($errno, $errstr, $errfile, $errline, $context) {
                if (self::$debug) {
                    echo sprintf(
                        '%s in %s line %s',
                        $errstr,
                        $errfile,
                        $errline
                    );
                } else {
                    echo 'error';
                }
                return 500;
            }
        );

        try {
            $controllerAction = self::getControllerAction();

            $response = call_user_func([$controllerAction['controller'], $controllerAction['action']]);
            self::$request->getSession()->save();

            return $response;
        } catch (\Throwable $e) {
            if (self::$debug) {
                return new Response(self::errorView($e), 500);
            }

            return new Response('error', 500);
        }
    }

    private static function errorView(Throwable $e)
    {
        return sprintf(
            '%s in %s line %s<br><pre>%s</pre>',
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    private static function getControllerAction()
    {
        $path = self::$request->getPathInfo();

        if ($path === '/') {
            $path = '/index';
        }

        return [
            'controller' => new DefaultController(self::$request),
            'action' => ltrim($path, '/') . 'Action'
        ];
    }
}