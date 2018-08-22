<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 16/07/18
 * Time: 19:06
 */

namespace App\Controller;

use http\Env\Request;

class PostsController extends AbstractController {

    public function getPosts() {

        try {
            $db = new \PDO('mysql:host=127.0.0.1;dbname=blogwritter;charset=utf8', "phpmyadmin", "root");
            // set the PDO error mode to exception
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        }
        catch(\Exception $e) {
            die('Error : '. $e->getMessage());
        }
    }

    /**
     * @param $id
     */
    public function show($id) {

        echo "<pre>";
        echo "Je suis l'article $id";
        echo "</pre>";

    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showPostsAction() {

        echo $this->render('/default/index.html.twig', array(null));
    }

    public function new() {


    }
}