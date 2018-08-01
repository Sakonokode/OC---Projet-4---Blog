<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 12:50
 */

namespace App\Entity;

use App\Annotation\EntityAnnotation;

/**
 * Class Users
 * @package App\Entity
 * @EntityAnnotation(
 *     table = "users"
 * )
 */
class Users extends Entity
{
    /** @var null|string $nickname */
    protected $nickname;

    protected $reports;

    protected $email;

    protected $password;


    public function getId()
    {

        return $this->id;
    }

    public function setId($id)
    {

        $id = (int)$id;

        if ($id > 0) {

            $this->id = $id;
        }

        return $this;
    }

    public function getNickname()
    {

        return $this->nickname;
    }

    public function setNickname($nickname)
    {

        if (is_string($nickname)) {

            $this->nickname = $nickname;
        }

        return $this;
    }

    public function getReports()
    {

        return $this->reports;
    }

    public function setReports($reports)
    {

        $this->reports = $reports;

        return $this;
    }

    public function getEmail()
    {

        return $this->email;
    }

    public function setEmail($email)
    {

        if (is_string($email)) {

            $this->email = $email;
        }

        return $this;
    }

    public function getPassword()
    {

        return $this->password;
    }

    public function setPassword($password)
    {

        if (is_string($password)) {

            $this->password = $password;
        }

        return $this;
    }


}