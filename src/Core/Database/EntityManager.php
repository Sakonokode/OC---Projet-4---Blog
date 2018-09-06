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
use Doctrine\Common\Annotations\AnnotationRegistry;
use PDO;

class EntityManager
{
    const DB_DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';
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
     * @return null|\PDOStatement
     */
    public function getCurrentStatement(): ?\PDOStatement
    {
        return $this->currentStatement;
    }

    /** @var null|EntityManager $instance */
    private static $instance = null;

    /**
     * EntityManager constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    private function __construct()
    {
        $this->reader = new AnnotationReader();
        $this->initConnection();
    }

    /**
     * Here we build a Singleton, the aim is to keep a unique EntityManager to avoid many connections to the Database
     * @return EntityManager
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public static function getInstance(): EntityManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
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

    /**
     * @param string $sql
     */
    public function query(string $sql): void
    {
        if (($stmt = $this->pdo->query($sql)) !== false) {
            $this->currentStatement = $stmt;
        }
    }

    /**
     * @param string $fetchStyle
     * @return array
     */
    public function fetchAll(string $fetchStyle = PDO::FETCH_CLASS): array
    {
        return $this->currentStatement->fetchAll($fetchStyle);
    }

    /**
     * To avoid EntityManager cloning, we overload the magic __clone function
     * @return null
     */
    public function __clone()
    {
        return null;
    }

    /**
     * To avoid EntityManager cloning, we overload the magic __wakeup function
     * @return null
     */
    public function __wakeup()
    {
        return null;
    }
}