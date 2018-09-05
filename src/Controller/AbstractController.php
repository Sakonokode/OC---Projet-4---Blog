<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 10:42
 */

namespace App\Controller;

use App\Core\Database\EntityManager;
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

    /** @var User $user|null */
    protected $user = null;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->user = $_SESSION['user'];
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
            return $_SESSION['user'] !== null;
        });
        $dateFormat = new \Twig_Function('date_format', function(\DateTime $date) {
            return date_format($date, EntityManager::DB_DEFAULT_DATE_FORMAT);
        });
        $this->twig->addFunction($isAuthenticated);
        $this->twig->addFunction($dateFormat);
    }

}