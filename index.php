<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

ini_set('display_errors', 1);

require_once "vendor/autoload.php";
require_once __DIR__ . '/src/model/register.php';
require_once __DIR__ . '/src/controller/PostsController.php';

$loader = new Twig_Loader_Array(array('index' => 'Hello {{name}}!',));
$twig = new Twig_Environment($loader);
echo $twig->render('index', array('name' => 'Fabien'));

echo("<pre>");
$postsController = new PostsController();
$postsController->getPosts();

var_dump($_SERVER["REQUEST_URI"]);
echo("</pre>");