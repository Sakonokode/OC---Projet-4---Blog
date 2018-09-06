<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 05/09/18
 * Time: 21:49
 */

namespace App\Core\Session;


use App\Core\Router\Router;
use App\Entity\User;

class Session
{
    /** @var User $user */
    protected $user;

    /** @var Router $router */
    protected $router;

    private function __construct()
    {
        $this->user = null;
    }

    public static function start(): void
    {
        session_start();
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    /**
     * @return Session
     */
    public static function getInstance(): Session
    {
        if (!isset( $_SESSION['session'])) {
            $_SESSION['session'] = new self();
        }

        return $_SESSION['session'];
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * @return null|User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}