<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 11:03
 */

namespace App\Core\Database;

use App\Entity\Posts;
use Doctrine\Common\Annotations\AnnotationReader;
use App\Annotation\EntityAnnotation;
use App\Entity\Entity;

class EntityManager
{
    /** @var string $dbHost */
    private $dbHost;

    /** @var string $dbName */
    private $dbName;

    /** @var string $dbUser */
    private $dbUser;

    /** @var string $dbPassword */
    private $dbPassword;

    /** @var \PDO $pdo */
    private $pdo;

    /** @var AnnotationReader $reader */
    private $reader;

    /**
     * EntityManager constructor.
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     * @param string $dbHost
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(string $dbName, string $dbUser, string $dbPassword, string $dbHost)
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbHost = $dbHost;
        $this->dbPassword = $dbPassword;
        $this->reader = new AnnotationReader();
    }

    /**
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        if ($this->pdo === null) {

            $connectionString = sprintf("mysql:dbname=%s;host=%s", $this->dbName, $this->dbHost);

            try {
                $pdo = new \PDO($connectionString, $this->dbUser, $this->dbPassword);
            }
            catch (\PDOException $e) {
                exit($e->getMessage());
            }
            $this->pdo = $pdo;
        }

        return $this->pdo;
    }

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     */
    public function insert(Entity $entity) {
        $className = get_class($entity);

        $reflectionClass = new \ReflectionClass($className);

        $annotations = $this->reader->getClassAnnotations($reflectionClass);

        dump($annotations);

        $sql = $annotations['insert'];
        $sql = sprintf($sql, $entity->getId());

        switch ($className) {
            case Posts::class:
                /** @var Posts $entity */
                $sql = sprintf($sql, $entity->getIdPostable());
                break;
        }
    }
}