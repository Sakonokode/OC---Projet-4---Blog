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

    /** @var string $name */
    private $name;

    /** @var string $path */
    private $path;

    /** @var string $callable */
    private $callable;

    /** @var array $matches */
    private $matches = [];

    /** @var array $params */
    private $params = [];

    /**
     * Route constructor.
     * @param string $name
     * @param string $path
     * @param string $callable
     */
    public function __construct(string $name, string $path, string $callable) {

        $this->name = $name;
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * @param $url
     * @return bool
     */
    public function match($url): bool {

        $url = trim($url, '/');

        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);

        $regex = "#^$path$#i";

        if (!preg_match_all($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);

        foreach ($matches as $value) {
            $this->matches[] = $value[0];
        }

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
     * @throws \Exception
     */
    public function call() {

        if ($this->callable !== null) {

            $params = explode('#', $this->callable);

            $controller = sprintf("App\\Controller\\%sController", $params[0]);

            $controller = new $controller();

            /* il faut creer un tableau associatif clef -> valeur, et l'envoyer a la place de $this->matches
            afin de pouvoir passer les parametres et leurs valeurs */
            return call_user_func_array([$controller, $params[1]], $this->matches);
        }

        throw new \Exception(sprintf("Unable to call %s", $this->callable));
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getCallable(): string
    {
        return $this->callable;
    }

    /**
     * @param string $callable
     */
    public function setCallable(string $callable): void
    {
        $this->callable = $callable;
    }

    /**
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @param array $matches
     */
    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->path;
    }
}