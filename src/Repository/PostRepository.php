<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 23/08/18
 * Time: 19:41
 */

namespace App\Repository;


use App\Entity\Content;
use App\Entity\Entity;
use App\Entity\Post;

class PostRepository extends Repository
{
    /**
     * PostRepository constructor.
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
        /** @var Post $entity */
        $content = $entity->getContent();
        $annotation = $this->readEntityAnnotation($entity);

        $repository = new ContentRepository();
        $repository->insertEntity($content);

        $params = self::buildInsertExecuteParams($entity);

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);
        $entity->setId($this->em->getLastInsertedId());
    }

    /**
     * @param Entity $post
     * @return array
     * @throws \Exception
     */
    public static function buildInsertExecuteParams(Entity $post): array
    {
        /** @var Post $post */
        if ($post->getContent()->getId() === null) {
            throw new \Exception('Missing content id');
        }

        $params = [
            'id_content' => $post->getContent()->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'slug' => $post->getSlug()
        ];

        return $params;
    }

    /**
     * @param Entity $post
     * @return array
     * @throws \Exception
     */
    public static function buildUpdateExecuteParams(Entity $post): array
    {
        /** @var Post $post */
        if ($post->getContent()->getId() === null) {
            throw new \Exception('Missing content id');
        }

        $params = [
            'id_content' => $post->getContent()->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'slug' => $post->getSlug(),
            'id' => $post->getId(),
        ];

        return $params;
    }

    /**
     * @param Entity $post
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteEntity(Entity $post): void
    {
        /** @var Post $post */
        $repository = new ContentRepository();
        $repository->deleteEntity($post->getContent());

        $annotation = $this->readEntityAnnotation($post);
        $params =  ['id' => $post->getId()];

        $sql = <<<EOT
        DELETE FROM $annotation->table WHERE id=:id;
EOT;

        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    /**
     * @param Entity $post
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateEntity(Entity $post): void
    {
        /** @var Post $post */
        $repository = new ContentRepository();
        $repository->updateEntity($post->getContent());

        $annotation = $this->readEntityAnnotation($post);
        $params = self::buildUpdateExecuteParams($post);

        dump($params);

        $sql = <<<EOT
        UPDATE $annotation->table
        SET  posts.id_content=:id_content, posts.title=:title, posts.description=:description, posts.slug=:slug
        WHERE posts.id=:id;

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
        $sql = <<<EOT
        SELECT p.id AS post_id, p.id_content AS post_id_content, p.title AS post_title, p.description AS post_description, p.slug AS post_slug, p.created_at AS post_created_at, p.updated_at AS post_updated_at, p.deleted_at AS post_deleted_at
        FROM posts AS p
        WHERE p.id = $id
EOT;

        $this->em->query($sql);
        $result = $this->em->fetchAll();

        if (!empty($result)) {
            $post = new Post();
            $post->setId($result[0]->post_id);
            $post->setTitle($result[0]->post_title);
            $post->setDescription($result[0]->post_description);
            $post->setSlug($result[0]->post_slug);
            $post->setCreated(new \DateTime($result[0]->post_created_at));
            $post->setUpdated(new \DateTime($result[0]->post_updated_at));

            if ($result[0]->post_deleted_at !== null) {
                $post->setDeleted(new \DateTime($result[0]->post_deleted_at));
            }

            $contentRepository = new ContentRepository();
            /** @var Content $content */
            $content = $contentRepository->findEntity($result[0]->post_id_content);

            $post->setContent($content);

            return $post;
        }

        return null;
    }
}