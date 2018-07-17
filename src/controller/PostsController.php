<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 16/07/18
 * Time: 19:06
 */

class PostsController {

    function getPosts() {

        try {
            $db = new PDO('mysql:host=127.0.0.1;dbname=blogwritter;charset=utf8', "phpmyadmin", "root");
            // set the PDO error mode to exception
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        }
        catch(Exception $e) {
            die('Error : '. $e->getMessage());
        }
    }
}