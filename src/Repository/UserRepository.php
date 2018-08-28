<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 28/08/18
 * Time: 22:39
 */

namespace App\Repository;


use App\Entity\Entity;
use App\Entity\User;

class UserRepository extends Repository
{

    /**
     * @param Entity $entity
     */
    public function insertEntity(Entity $entity): void
    {
        // TODO: Implement insertEntity() method.
    }

    /**
     * @param Entity $entity
     */
    public function updateEntity(Entity $entity): void
    {
        // TODO: Implement updateEntity() method.
    }

    /**
     * @param Entity $entity
     * @return array
     */
    public static function buildInsertExecuteParams(Entity $entity): array
    {
        // TODO: Implement buildInsertExecuteParams() method.
    }

    /**
     * @param Entity $entity
     */
    public function deleteEntity(Entity $entity): void
    {
        // TODO: Implement deleteEntity() method.
    }

    /**
     * @param int $id
     * @return Entity|null
     */
    public function findEntity(int $id): ?Entity
    {
        // Syntaxe Heredoc
        // Here we use SQL Left join
        $sql = <<<EOT
        SELECT u.id AS user_id, u.nickname AS user_nickname, u.email AS user_email, u.password AS user_password, u.created_at AS user_created_at, u.updated_at AS user_updated_at, u.deleted_at AS user_deleted_at
        FROM users AS u  
        WHERE u.id = $id
EOT;

        $this->em->query($sql);
        $result = $this->em->fetchAll();

        if (!empty($result)) {
            $user = new User();
            $user->setId($result[0]->user_id);
            $user->setNickname($result[0]->user_nickname);
            $user->setEmail($result[0]->user_email);
            $user->setPassword($result[0]->user_password);
            $user->setCreated(new \DateTime($result[0]->user_created_at));
            $user->setUpdated(new \DateTime($result[0]->user_updated_at));

            if ($result[0]->user_deleted_at !== null) {
                $user->setDeleted(new \DateTime($result[0]->user_deleted_at));
            }

            return $user;
        }

        return null;
    }
}