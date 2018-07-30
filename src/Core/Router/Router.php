<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 24/07/18
 * Time: 17:26
 */

namespace App\Core\Router;


use App\Exceptions\RouterException;

class Router
{

    /** @var string $url */
    private $url;

    /** @var array $routes */
    private $routes = [];

    /**
     * Router constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {

        $this->url = $url;
    }

    /**
     * @param $path
     * @param $callable
     */
    public function get($path, $callable): void
    {

        $route = new Route($path, $callable);

        $this->routes['GET'][] = $route;
    }

    /**
     * @param $path
     * @param $callable
     */
    public function post($path, $callable): void
    {

        $route = new Route($path, $callable);

        $this->routes['POST'][] = $route;
    }

    public function run()
    {

        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {

            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {

            if ($route->match($this->url)) {

                return $route->call();
            }
        }

        throw new RouterException('No matching routes');
    }
}