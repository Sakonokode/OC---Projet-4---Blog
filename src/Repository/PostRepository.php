<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 23/08/18
 * Time: 19:41
 */

namespace App\Repository;


use App\Core\Database\EntityManager;
use App\Entity\Content;
use App\Entity\Entity;
use App\Entity\Post;

/**
 * Class PostRepository
 * @package App\Repository
 */
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
            'slug' => $post->getSlug(),
            'deleted_at' => $post->getDeleted(),
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
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'slug' => $post->getSlug(),
            'updated_at' => $post->getUpdated()->format(EntityManager::DB_DEFAULT_DATE_FORMAT),
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
        /** @var ContentRepository $contentRepository */
        $contentRepository = new ContentRepository();
        $contentRepository->deleteEntity($post->getContent());

        $annotation = $this->readEntityAnnotation($post);
        $params =  ['id' => $post->getId()];

        $sql = <<<EOT
        DELETE FROM $annotation->table WHERE id = :id;
EOT;

        $this->em->prepare($sql);
        $this->em->execute($params);
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
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateEntity(Entity $post): void
    {
        /** @var Post $post */
        $content = $post->getContent();
        $annotation = $this->readEntityAnnotation($post);

        $repository = new ContentRepository();
        $repository->updateEntity($content);

        $params = self::buildUpdateExecuteParams($post);

        $this->em->prepare($annotation->update);
        $this->em->execute($params);
    }

    /**
     * @param int $id
     * @return Entity|null
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
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
            $commentRepository = new CommentRepository();

            /** @var Content $content */
            $content = $contentRepository->findEntity($result[0]->post_id_content);
            $comments = $commentRepository->findPostComments($id);

            if ($comments != null) {
                $post->setComments($comments);
            }

            $post->setContent($content);

            return $post;
        }

        return null;
    }

    /**
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function findAllEntity(): array
    {
        $entities = [];
        $sql = <<<EOT
        SELECT p.id AS post_id, p.id_content AS post_id_content, p.title AS post_title, p.description AS post_description, p.slug AS post_slug, p.created_at AS post_created_at, p.updated_at AS post_updated_at, p.deleted_at AS post_deleted_at
        FROM posts AS p
        WHERE p.deleted_at IS NULL
EOT;
        $this->em->query($sql);
        $results = $this->em->fetchAll();

        if (!empty($results)) {

            foreach ($results as $values) {
                $entities[] = self::toEntity($values);
            }
        }

        return $entities;
    }

    /**
     * @param object $values
     * @return Entity
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function toEntity($values): Entity
    {
        $post = new Post();
        $post->setId($values->post_id);
        $post->setTitle($values->post_title);
        $post->setDescription($values->post_description);
        $post->setSlug($values->post_slug);
        $post->setCreated(new \DateTime($values->post_created_at));
        $post->setUpdated(new \DateTime($values->post_updated_at));

        if ($values->post_deleted_at !== null) {
            $post->setDeleted(new \DateTime($values->post_deleted_at));
        }

        $contentRepository = new ContentRepository();
        /** @var Content $content */
        $content = $contentRepository->findEntity($values->post_id_content);

        $post->setContent($content);

        return $post;
    }
}