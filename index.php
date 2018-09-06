<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

use App\Core\Router\Router;
use App\Core\Session\Session;
use Doctrine\Common\Annotations\AnnotationRegistry;

ini_set('display_errors', 1);
require_once "vendor/autoload.php";
Session::start();
# To avoid DocParser error, 'class not found'
AnnotationRegistry::registerLoader('class_exists');

/** @var Router $router */
require_once __DIR__ . '/src/Core/Router/routes.php';
