<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 16/07/18
 * Time: 18:42
 */

namespace App\Controller;

class HomeController extends AbstractController
{

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function homeAction()
    {

        echo $this->render('default/homepage.html.twig', []);
    }
}