<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 24/07/18
 * Time: 17:26
 */

namespace App\router;


class router {

    private $url;
    private $routes = [];

    public function __construct($url) {

        $this->url = $url;
    }

    public function get($path, $callable) {

        $route = new route($path, $callable);

        $this->routes['GET'][] = $route;
    }

    public function post($path, $callable) {

        $route = new route($path, $callable);

        $this->routes['POST'][] = $route;
    }

    public function run() {

        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){

            throw new routerException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {

            if($route->match($this->url)){

                return $route->call();
            }
        }

        throw new routerException('No matching routes');
    }
}