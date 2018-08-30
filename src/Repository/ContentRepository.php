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
     * @param Entity $content
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insertEntity(Entity $content): void
    {
        /** @var Content $entity */
        $annotation = $this->readEntityAnnotation($content);

        $params = self::buildInsertExecuteParams($content);

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);

        $content->setId($this->em->getLastInsertedId());
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
     * @return array
     */
    public static function buildUpdateExecuteParams(Entity $entity): array
    {
        /** @var Content $entity */
        return [
            'author' => $entity->getAuthor()->getId(),
            'content' => $entity->getContent(),
            'id' => $entity->getId(),
        ];
    }

    /**
     * @param Entity $content
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateEntity(Entity $content): void
    {
        /** @var Content $content */

        $annotation = $this->readEntityAnnotation($content);
        $params = self::buildUpdateExecuteParams($content);

        dump($params);

        $sql = <<<EOT
        UPDATE $annotation->table
        SET  content.author=:author, content.content=:content
        WHERE content.id=:id;

EOT;

        $this->em->prepare($sql);
        $this->em->execute($params);
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