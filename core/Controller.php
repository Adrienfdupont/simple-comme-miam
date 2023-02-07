<?php

class Controller
{
    // les variables qui seront retournées dans la vue
    public static $data;

    // le contenu spécifique à une page si un layout en a besoin
    public static $content;

    public static function view(array $content, array $data = null): void
    {
        define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views');

        self::$data = $data;

        // si layout
        if (count($content) === 2) {
            self::$content = VIEWS . DIRECTORY_SEPARATOR . $content[0] . '.php';
            require VIEWS . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $content[1] . '.php';

            // si pas de layout
        } else if (count($content) === 1) {
            require VIEWS . DIRECTORY_SEPARATOR . $content[0] . '.php';
        }
        exit();
    }
}
