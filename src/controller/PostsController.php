<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 16/07/18
 * Time: 19:06
 */

function getPosts() {

    try {
        $db = new PDO('mysql:host=localhost;dbname=test;charset=utf8\', \'root\', \'root\'');
    }

    catch(Exception $e) {
        die('Error : '.$e->getMessage());
    }
}

