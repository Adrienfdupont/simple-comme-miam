<?php

class Route
{
    public static $controller;

    public static $method;

    public static $param;

    // faire le lien entre une URL et un contrôleur
    public static function call(string $path, array $action): void
    {
        // vérifier si une URL a bien été rentrée
        if (isset($_SERVER['REQUEST_URI'])) {

            // vérifier si l'URL rentrée correspond à l'URL attendue
            if ($path == self::cleanURL($_SERVER['REQUEST_URI'])) {
                self::$controller = $action[0];
                self::$method = $action[1];
                self::callController();
            }
        }
    }

    // nettoyer l'URL des variables
    public static function cleanURL($url)
    {
        $expectedPath = explode('?', $url);
        $expectedPath = $expectedPath[0];
        return $expectedPath;
    }

    // on appelle le contrôleur
    public static function callController(): void
    {
        if (class_exists(self::$controller)) {

            if (method_exists(self::$controller, self::$method)) {
                (self::$controller::{self::$method}());
            }
        } else {
            echo 'Le contrôleur n\'est pas défini';
        }
    }
}
