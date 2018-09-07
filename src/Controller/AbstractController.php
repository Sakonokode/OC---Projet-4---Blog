<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 10:42
 */

namespace App\Controller;

use App\Core\Database\EntityManager;
use App\Core\Session\Session;
use App\Entity\User;

abstract class AbstractController
{
    /** @var string $dbName */
    private static $dbName = 'blogwritter';

    /** @var string $dbUser */
    private static $dbUser = 'phpmyadmin';

    /** @var string $dbPassword */
    private static $dbPassword = 'root';

    /** @var string $dbHost */
    private static $dbHost = 'localhost';

    /** @var EntityManager $entityManager */
    private static $entityManager = null;

    /** @var \Twig_Loader_Filesystem $loader */
    protected $loader;

    /** @var \Twig_Environment $twig */
    protected $twig;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $view
     * @param array $data
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($view, array $data)
    {
        $this->loader = new \Twig_Loader_Filesystem('/home/coltluger/Web/src/views');
        $this->twig = new \Twig_Environment($this->loader);
        $this->addTwigFunctions();
        return $this->twig->render($view, $data);
    }

    public function addTwigFunctions(): void
    {
        $isAuthenticated = new \Twig_Function('is_authenticated', function() {
            return (Session::getInstance()->getUser() !== null);
        });

        $dateFormat = new \Twig_Function('date_format', function(\DateTime $date) {
            return date_format($date, EntityManager::DB_DEFAULT_DATE_FORMAT);
        });

        $getUser = new \Twig_Function('get_user', function() {
            return Session::getInstance()->getUser();
        });

        $this->twig->addFunction($isAuthenticated);
        $this->twig->addFunction($dateFormat);
        $this->twig->addFunction($getUser);
    }

    /**
     * @param string $route
     * @param array $params
     * @param string $method
     */
    public function redirect(string $route, array $params, string $method): void
    {
        Session::getInstance()->getRouter()->redirect($route, $params, $method);
    }
}