<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 11:03
 */

namespace App\Core\Database;

use Doctrine\Common\Annotations\AnnotationReader;
use App\Entity\Entity;
use PDO;

class EntityManager
{
    const DB_HOST = '127.0.0.1';
    const DB_USER = 'phpmyadmin';
    const DB_PASS = 'root';
    const DB_NAME = 'blogwritter';

    /** @var string $dbHost */
    private $dbHost = self::DB_HOST;

    /** @var string $dbName */
    private $dbName = self::DB_NAME;

    /** @var string $dbUser */
    private $dbUser = self::DB_USER;

    /** @var string $dbPassword */
    private $dbPassword = self::DB_PASS;

    /** @var \PDO $pdo */
    private $pdo;

    /** @var AnnotationReader $reader */
    private $reader;

    /** @var null|\PDOStatement $currentStatement */
    private $currentStatement = null;

    /**
     * EntityManager constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        $this->reader = new AnnotationReader();
        $this->initConnection();
    }

    /**
     * @param string|null $dbName
     * @param string|null $dbUser
     * @param string|null $dbPassword
     * @param string|null $dbHost
     */
    public function setConnectionData(string $dbName = null, string $dbUser = null, string $dbPassword = null, string $dbHost = null): void
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbHost = $dbHost;
        $this->dbPassword = $dbPassword;
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

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    /**
     * @return AnnotationReader
     */
    public function getReader(): AnnotationReader
    {
        return $this->reader;
    }

    /**
     * @param string|null $name
     * @return int
     */
    public function getLastInsertedId(string $name = null): int
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();

    }

    /**
     * @param string $sql
     */
    public function prepare(string $sql): void
    {
        $this->currentStatement = $this->pdo->prepare($sql);
    }

    /**
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function execute(array $params): bool
    {
        $result = $this->currentStatement->execute($params);
        if (!$result){
            throw new \Exception("error in execute");
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
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