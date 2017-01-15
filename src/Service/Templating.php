<?php

namespace Service;

use Symfony\Component\HttpFoundation\Response;

class Templating
{
    /**
     * @return Response
     */
    public function render($template, array $data = [], $useLayout = true)
    {
        $templatePath = __DIR__.'/../View/'.$template;
        if (!is_file($templatePath)) {
            throw new \Exception('Template not found');
        }

        $session = \Application::$request->getSession();

        ob_start();
        require $templatePath;
        $content = ob_get_clean();

        if ($useLayout) {
            ob_start();
            require __DIR__.'/../View/layout.php';
            $content = ob_get_clean();
        }

        return $content;
    }
}