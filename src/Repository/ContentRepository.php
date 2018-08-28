<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 23/08/18
 * Time: 22:00
 */

namespace App\Repository;


use App\Entity\Content;
use App\Entity\Entity;
use App\Entity\Post;
use App\Entity\User;

class ContentRepository extends Repository
{
    /**
     * ContentRepository constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insertEntity(Entity $entity): void
    {
        /** @var Content $entity */
        $annotation = $this->readEntityAnnotation($entity);

        $params = self::buildInsertExecuteParams($entity);

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);

        $entity->setId($this->em->getLastInsertedId());
    }

    /**
     * @param Entity $entity
     * @return array
     */
    public static function buildInsertExecuteParams(Entity $entity): array
    {
        /** @var Content $entity */
        return [
            'author' => $entity->getAuthor()->getId(),
            'content' => $entity->getContent(),
        ];
    }

    /**
     * @param Entity $entity
     */
    public function updateEntity(Entity $entity): void
    {
        // TODO: Implement updateEntity() method.
    }

    /**
     * @param Entity $content
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteEntity(Entity $content): void
    {
        /** @var Content $content */
        $annotation = $this->readEntityAnnotation($content);
        $params =  ['id' => $content->getId()];

        $sql = <<<EOT
        DELETE FROM $annotation->table WHERE id=:id;
EOT;
        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    /**
     * @param int $id
     * @return Entity|null
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function findEntity(int $id): ?Entity
    {
        // Syntaxe Heredoc
        // Here we use SQL Left join
        $sql = <<<EOT
        SELECT 
        c.id AS content_id, c.author AS content_author, c.content AS content_content, c.created_at AS content_created_at, c.updated_at AS content_updated_at, c.deleted_at AS content_deleted_at  
        FROM content AS c
        WHERE c.id = $id
EOT;

        $this->em->query($sql);
        $result = $this->em->fetchAll();

        if (!empty($result)) {
            $content = new Content();
            $content->setId($result[0]->content_id);
            $userRepository = new UserRepository();
            /** @var User $user */
            $user = $userRepository->findEntity($result[0]->content_author);

            $content->setAuthor($user);
            $content->setContent($result[0]->content_content);
            $content->setCreated(new \DateTime($result[0]->content_created_at));
            $content->setUpdated(new \DateTime($result[0]->content_updated_at));

            if ($result[0]->content_deleted_at !== null) {
                $content->setDeleted(new \DateTime($result[0]->content_deleted_at));
            }

            return $content;
        }

        return null;
    }
}