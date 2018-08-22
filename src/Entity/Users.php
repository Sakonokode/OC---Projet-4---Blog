<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 12:50
 */

namespace App\Entity;

use App\Annotations\EntityAnnotation;

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

    /** @var string $email */
    protected $email;

    /** @var string $password */
    protected $password;

    /**
     * @return null|string
     */
    public function getNickname()
    {

        return $this->nickname;
    }

    /**
     * @param $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {

        if (is_string($nickname)) {

            $this->nickname = $nickname;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReports()
    {

        return $this->reports;
    }

    /**
     * @param $reports
     * @return $this
     */
    public function setReports($reports)
    {

        $this->reports = $reports;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {

        if (is_string($email)) {

            $this->email = $email;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {

        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {

        if (is_string($password)) {

            $this->password = $password;
        }

        return $this;
    }


    /**
     * @param array $data
     * @return Entity
     */
    public static function instantiate(array $data): Entity
    {
        $user = new self();
        $user->setId($data['id']);
        $user->setNickname($data['nickname']);
        $user->setEmail($data['email']);
        $user->setReports($data['reports']);

        return $user;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'id' => $this->id,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'reports' => $this->reports,
            'created_at' => $this->created
        ];
    }
}