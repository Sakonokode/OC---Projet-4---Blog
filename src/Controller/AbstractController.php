<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 10:42
 */

namespace App\Controller;

use App\Core\Database\EntityManager;

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
     * @return EntityManager
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public static function getEntityManager()
    {

        if (self::$entityManager === null) {

            self::$entityManager = new EntityManager(self::$dbName, self::$dbHost, self::$dbUser, self::$dbPassword);
        }
        return self::$entityManager;
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

        return $this->twig->render($view, $data);
    }
}