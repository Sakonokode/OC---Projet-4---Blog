<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

ini_set('display_errors', 1);

require_once __DIR__ . '/src/model/register.php';
require_once __DIR__ . '/src/controller/PostsController.php';

echo("<pre>");
$postsController = new PostsController();
$postsController->getPosts();

var_dump($_SERVER["REQUEST_URI"]);
echo("</pre>");