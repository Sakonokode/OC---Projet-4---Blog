<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 05/09/18
 * Time: 09:32
 */

namespace App\Controller;


use App\Core\Session\Session;
use App\Entity\User;
use App\Repository\Repository;
use App\Repository\UserRepository;

class SecurityController extends AbstractController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function authAction() {
        echo $this->render('/security/login.html.twig', []);
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Exception
     */
    public function checkAuthAction() {
        $userEmail = $_POST['_email'];
        $encryptedPassword = sha1($_POST['_password']);
        $repository = new UserRepository();

        /** @var User $user */
        $user = $repository->findEntityByEmail($userEmail);

        if ($user === null) {
            throw new \Exception("This user does not exist");
        }

        if ($encryptedPassword === $user->getPassword()) {
            $user->setPassword(null);
            Session::getInstance()->setUser($user);

            $this->redirect('home', [], 'GET');
        }else {
            throw new \Exception("Incorrect Password");
        }
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function subscribeAction() {
        echo $this->render('/security/subscribe.html.twig', []);
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function checkSubscribtionAction() {

        $userEmail = $_POST['_email'];
        $encryptedPassword = sha1($_POST['_password']);
        $encryptedConfPass = sha1($_POST['_password-confirmation']);
        $repository = new UserRepository();
        $dbEmail = $repository->findEntityByEmail($userEmail);

        if (!($dbEmail === null)) {
            throw new \Exception("This email already exists");
        }

        if ($encryptedConfPass === $encryptedPassword) {

            $user = new User();
            $user->setNickname($_POST['_nickname']);
            $user->setEmail($_POST['_email']);
            $user->setPassword($encryptedPassword);
            $user->setRole(2);
            $repository->insert($user);
        }
        echo $this->render('/blog/index.html.twig', []);
    }

    public function logoutAction() {
        Session::destroy();
        Session::getInstance()->getRouter()->redirect('home', [], 'GET');
    }
}