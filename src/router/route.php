<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 24/07/18
 * Time: 17:32
 */

namespace App\router;


class route {

    private $path;
    private $callable;
    private $matches;

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

            $controller = "App\\controller\\" . $params[0] . "Controller";

            $controller = new $controller();

            return call_user_func_array([$controller, $params[1]], $this->matches);
        }

        else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }
}