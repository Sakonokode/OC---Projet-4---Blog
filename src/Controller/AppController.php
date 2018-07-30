<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 10:42
 */

namespace App\Controller;

use App\Core\Database\EntityManager;

abstract class AppController {

    private $dbName = 'blogwritter';
    private $dbUser = 'phpmyadmin';
    private $dbPassword = 'root';
    private $dbHost = 'localhost';
    private $dbInstance;

    public function load() {

        session_start();

        require_once __DIR__ . '/../../src/Repository/register.php';
        require_once __DIR__ . '/../../src/Controller/PostsController.php';

    }

    public function getDatabase() {

        if (is_null($this->dbInstance)) {

            $this->dbInstance = new EntityManager($this->dbName, $this->dbHost, $this->dbUser, $this->dbPassword);
        }
        return $this->dbInstance;
    }
}