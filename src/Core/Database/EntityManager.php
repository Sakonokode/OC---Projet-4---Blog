<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/07/18
 * Time: 11:03
 */

namespace App\Core\Database;

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

    public function __construct(string $dbName, string $dbUser, string $dbPassword, string $dbHost)
    {
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbHost = $dbHost;
        $this->dbPassword = $dbPassword;
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
}