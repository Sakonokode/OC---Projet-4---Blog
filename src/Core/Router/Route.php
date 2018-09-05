<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 24/07/18
 * Time: 17:32
 */

namespace App\Core\Router;


use App\Repository\PostRepository;

class Route {

    /**
     * @var string $path
     */
    private $path;


    private $callable;

    /** @var array $matches */
    private $matches = [];

    /** @var array $params */
    private $params = [];

    public function __construct($path, $callable) {

        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * @param $url
     * @return bool
     */
    public function match($url) {

        $url = trim($url, '/');

        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);

        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {

            return false;
        }

        array_shift($matches);

        $this->matches = $matches;

        return true;
    }

    private function paramMatch($match) {

        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    /**
     * @return mixed
     */
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

    /**
     * @param $param
     * @param $regex
     * @return $this
     */
    public function with($param, $regex) {

        $this->params[$param] = str_replace('(', '(?:', $regex);

        return $this;
    }
}