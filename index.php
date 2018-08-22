<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

use App\Entity\Posts;

ini_set('display_errors', 1);
require_once "vendor/autoload.php";
require_once __DIR__ . '/src/Core/Router/routes.php';
session_start();



try{
    $post = new Posts();
    $post->setTitle('titre test');
    $post->setAuthor('auteur test');
    $post->setContent('contenu test');

    $dbName = "blogwritter";
    $dbUser = 'phpmyadmin';
    $dbPassword = 'root';
    $dbHost = '127.0.0.1';

    $em = new \App\Core\Database\EntityManager($dbName, $dbUser, $dbPassword, $dbHost);
    $em->insert($post);
    throw new \Exception("toto");

} catch (\Exception $e) {
    echo '<pre>';
    echo sprintf('Exception - file<br/> %s, line<br/> %d, message<br/> %s <br/> Stack Trace <br/> %s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
    echo '</pre>';
    exit("error in index.php");
}

