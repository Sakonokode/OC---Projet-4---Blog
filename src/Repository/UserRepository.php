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

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends Repository
{

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insertEntity(Entity $entity): void
    {
        $annotation = $this->readEntityAnnotation($entity);
        $params = self::buildInsertExecuteParams($entity);

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);

        $entity->setId($this->em->getLastInsertedId());
    }

    /**
     * @param Entity $user
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateEntity(Entity $user): void
    {
        /** @var User $user */
        $annotation = $this->readEntityAnnotation($user);
        $params = self::buildUpdateExecuteParams($user);

        $sql = <<<EOT
        UPDATE $annotation->table
        SET  users.nickname=:nickname, users.email=:email, users.password=:password, users.role=:role
        WHERE users.id=:id;

EOT;

        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    /**
     * @param Entity $user
     * @return array
     */
    public static function buildInsertExecuteParams(Entity $user): array
    {
        /** @var User $user */
        $params = [
            'nickname' => $user->getNickname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ];

        return $params;
    }

    /**
     * @param Entity $user
     * @return array
     */
    public static function buildUpdateExecuteParams(Entity $user): array
    {
        /** @var User $user */
        $params = [
            'nickname' => $user->getNickname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'id' => $user->getId(),
        ];

        return $params;
    }

    /**
     * @param Entity $user
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteEntity(Entity $user): void
    {
        /** @var User $user */
        $annotation = $this->readEntityAnnotation($user);
        dump($user);
        $params =  ['id' => $user->getId()];

        $sql = <<<EOT
        DELETE FROM $annotation->table WHERE id=:id;
EOT;
        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    /**
     * @param int $id
     * @return Entity|null
     */
    public function findEntity(int $id): ?Entity
    {
        // Syntaxe Heredoc
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

    public function findAllEntity(): array
    {
        // TODO: Implement findAllEntity() method.
    }

    public function toEntity(array $values): Entity
    {
        // TODO: Implement toEntity() method.
    }
}