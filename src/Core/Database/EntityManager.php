<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 11:03
 */

namespace App\Core\Database;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Users;
use Doctrine\Common\Annotations\AnnotationReader;
use App\Entity\Entity;
use PDO;

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
        $this->initConnection();
    }

    /**
     * Initialize the connection to the DB
     */
    protected function initConnection(): void
    {
        if ($this->pdo === null) {

            $connectionString = sprintf("mysql:dbname=%s;host=%s", $this->dbName, $this->dbHost);

            try {
                $pdo = new \PDO($connectionString, $this->dbUser, $this->dbPassword);
            } catch (\PDOException $e) {
                exit($e->getMessage());
            }
            $this->pdo = $pdo;
        }
    }

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     */
    public function insert(Entity $entity)
    {
        $params = [];
        #$className = array_reverse(explode('\\', get_class($entity)))[0];
        $className = get_class($entity);
        $reflectionClass = new \ReflectionClass($className);
        dump($className);
        $annotations = $this->reader->getClassAnnotations($reflectionClass);

        dump($annotations);

        $sql = $annotations[0]['insert'];

        dump($annotations);

        $sql = sprintf($sql, $entity->getId());

        switch ($className) {
            case Posts::class:
                /** @var Posts $entity */
                $sql = sprintf($sql, $entity->getIdPostable());
                $params = $entity->__toArray();
                break;
            case Users::class:
                break;
            case Comments::class:
                break;
        }
        dump($params);

        $this->pdo->beginTransaction();
        $stmt = $this->pdo->prepare($sql, $params);
        if (!$this->pdo->exec($stmt)){
            throw new \Exception("error in insert func");
        }
        $this->pdo->commit();
    }

    /**
     * @param string $entityClassName
     * @param int $id
     * @throws \ReflectionException
     * @return Entity
     */
    public function get(string $entityClassName, int $id): Entity
    {
        $params = [];

        $reflectionClass = new \ReflectionClass($entityClassName);

        $annotations = $this->reader->getClassAnnotations($reflectionClass);

        dump($annotations);

        $sql = $annotations[0]['get'];
        $sql = sprintf($sql, $id);

        dump($annotations[0]['table']);

        switch ($annotations[0]['table']) {
            case Posts::class:
                /** @var Posts $entity */
                $sql = sprintf($sql, $entity->getIdPostable());
                $params = $entity->__toArray();
                break;
            case Users::class:
                /** @var Users $entity */
                $sql = sprintf($sql, $entity->getId());
                $params = $entity->__toArray();
                break;
            case Comments::class:
                break;
        }
        dump($params);

        $stmt = $this->pdo->prepare($sql, $params);

        if (!$this->pdo->exec($stmt)) {
            throw new \Exception("error in get func");
        }

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        $entity = Posts::instantiate($result);

        return $entity;
    }

    /*
    public function all(string $entityClassName, array $orderBy = []): ?array
    {
        # Grace a entity class name je lit l'annotation sur l'entite
        #$annotation = Code pour lire l'annotation

        $params = [];

        $reflectionClass = new \ReflectionClass($entityClassName);

        $annotations = $this->reader->getClassAnnotations($reflectionClass);

        dump($annotations);

        $sql = sprintf('SELECT * FROM %s', $annotations[0]['table']);
        isset($orderBy['column'] ? $column = isset($orderBy['column']) : $column = 'created_at';
        isset($orderBy['order'] ? $order = isset($orderBy['order']) : $order = 'desc';
        $sql = sprintf('%s ORDER BY %s %s', $sql, $column, $order);

        return $this->pdo->exec($sql, PDO::FETCH_ASSOC);
    }
    */
}