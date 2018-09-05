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

    /** @var array $namedRoutes */
    private $namedRoutes = [];

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
     * @param null $name
     * @return Route
     */
    public function get($path, $callable, $name = null): Route
    {
        $route = new Route($path, $callable);
        $this->routes['GET'][] = $route;

        return $route;
    }

    /**
     * @param $path
     * @param $callable
     * @param null $name
     * @return Route
     */
    public function post($path, $callable, $name = null): Route
    {
        $route = new Route($path, $callable);
        $this->routes['POST'][] = $route;

        return $route;
    }


    /**
     * @return mixed
     */
    public function run()
    {

        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {

            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {

            /** @var Route $route */
            if ($route->match($this->url)) {

                return $route->call();
            }
        }

        throw new RouterException('No matching routes');
    }
}