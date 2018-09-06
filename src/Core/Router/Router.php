<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 24/07/18
 * Time: 17:26
 */

namespace App\Core\Router;


use App\Core\Session\Session;
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
     * @param string $name
     * @param string $method
     * @param string $path
     * @param string $callable
     * @return Route
     */
    public function route(string $name, string $method, string $path, string $callable): Route
    {
        $route = new Route($name, $path, $callable);
        $this->routes[strtoupper($method)][$name] = $route;

        return $route;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function run()
    {
        if (empty($this->url)) {
            $this->redirect('home', [], 'GET');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $name => $route) {

            /** @var Route $route */
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new RouterException(sprintf('No matching route %s', $route));
    }

    /**
     * @param string $name
     * @param array $params
     * @param string $method
     * @return null|string
     */
    public function generate(string $name, array $params, string $method = 'GET'): ?string
    {
        /** @var Route $route */
        foreach ($this->routes[$method] as $currentName => $route) {
            if ($currentName === $name) {
                $url = $route->getPath();

                foreach ($params as $name => $value) {
                    if (preg_match('#(:' . $name . ')+#', $url)) {
                        $url = str_replace(':' . $name, $value, $url);
                    }
                }

                return '/' . $url;
            }
        }
        return null;
    }

    /**
     * @param string $route
     * @param array $params
     * @param string $method
     */
    public function redirect(string $route, array $params, string $method)
    {
        $router = Session::getInstance()->getRouter();
        $location = sprintf('Location: %s', $router->generate($route, $params, $method));
        header($location);
    }
}