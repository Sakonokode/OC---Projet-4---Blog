<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 24/07/18
 * Time: 17:32
 */

namespace App\Core\Router;


class Route {

    /**
     * @var string $path
     */
    private $path;


    private $callable;

    /** @var array $matches */
    private $matches = [];

    public function __construct($path, $callable) {

        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match($url) {

        $url = trim($url, '/');

        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);

        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {

            return false;
        }

        array_shift($matches);

        $this->matches = $matches;

        return true;
    }

    public function call() {

        if (is_string($this->callable)) {

            $params = explode('#', $this->callable);

            $controller = sprintf("App\\Controller\\%sController", $params[0]);

            $controller = new $controller();

            /* il faut creer un tableau associatif clef -> valeur, et l'envoyer a la place de $this->matches
            afin de pouvoir passer les parametres et leurs valeurs */
            return call_user_func_array([$controller, $params[1]], $this->matches);
        }

        else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}