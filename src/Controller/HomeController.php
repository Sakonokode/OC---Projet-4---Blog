<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 16/07/18
 * Time: 18:42
 */

namespace App\Controller;

class HomeController extends AbstractController {

    public function homeAction() {

        /*
        $this->load();
        */

        echo $this->render('default/homepage.html.twig', array(null));
    }
}