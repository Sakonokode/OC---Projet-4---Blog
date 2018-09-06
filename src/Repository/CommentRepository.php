<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/08/18
 * Time: 13:50
 */

namespace App\Repository;


use App\Core\Database\EntityManager;
use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\Entity;
use App\Entity\Post;
use App\Entity\User;

/**
 * Class CommentRepository
 * @package App\Repository
 */
class CommentRepository extends Repository
{

    /**
     * CommentRepository constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Entity $comment
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insertEntity(Entity $comment): void
    {
        /** @var Comment $comment */
        $content = $comment->getContent();

        $annotation = $this->readEntityAnnotation($comment);

        $repository = new ContentRepository();
        $repository->insertEntity($content);

        $params = self::buildInsertExecuteParams($comment);

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);
        $comment->setId($this->em->getLastInsertedId());
    }

    /**
     * @param Entity $comment
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function updateEntity(Entity $comment): void
    {
        /** @var Comment $comment */
        $content = $comment->getContent();
        $annotation = $this->readEntityAnnotation($comment);

        $repository = new ContentRepository();
        $repository->updateEntity($content);

        $params = self::buildUpdateExecuteParams($comment);

        $this->em->prepare($annotation->update);
        $this->em->execute($params);
    }

    /**
     * @param Entity $comment
     * @return array
     * @throws \Exception
     */
    public static function buildInsertExecuteParams(Entity $comment): array
    {
        /** @var Comment $comment */
        if ($comment->getContent()->getId() === null) {
            throw new \Exception('Missing content id');
        }

        $params = [
            'id_content' => $comment->getContent()->getId(),
            'id_post' => $comment->getPost()->getId(),
            'deleted_at' => $comment->getDeleted(),
        ];

        return $params;
    }

    /**
     * @param Entity $comment
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteEntity(Entity $comment): void
    {
        /** @var Comment $comment */
        $repository = new ContentRepository();


        $annotation = $this->readEntityAnnotation($comment);
        $params =  ['id' => $comment->getId()];

        $sql = <<<EOT
        DELETE FROM $annotation->table WHERE id = :id;
EOT;

        $repository->deleteEntity($comment->getContent());
        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    /**
     * @param int $id
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function findPostComments(int $id): ?array
    {
        $comments = [];
        $sql = <<<EOT
        SELECT *
        FROM comments AS c
        WHERE c.id_post = $id
EOT;
        $this->em->query($sql);
        $results = $this->em->fetchAll();

        if (!empty($results)) {

            foreach ($results as $values) {
                $comments[] = self::toEntity($values);
            }

            return $comments;
        }

        return null;
    }

    /**
     * @param int $id
     * @return Entity|null
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function findEntity(int $id): ?Entity
    {
        /** @var ContentRepository $contentRepository */
        $commentRepository = new CommentRepository();

        /** @var PostRepository $PostRepository */
        $postRepository = new PostRepository();

        // Syntaxe Heredoc
        $sql = <<<EOT
        SELECT 
        c.id AS comment_id, c.id_content AS comment_id_content, c.id_post AS comment_id_post, c.created_at AS comment_created_at, c.updated_at AS comment_updated_at, c.deleted_at AS comment_deleted_at  
        FROM comments AS c
        WHERE c.id = $id
EOT;

        $this->em->query($sql);
        $result = $this->em->fetchAll();

        if (!empty($result)) {

            /** @var Content $content */
            $content = $commentRepository->find(Content::class, $result[0]->comment_id_content);

            /** @var Post $post */
            $post = $postRepository->find(Post::class, $result[0]->comment_id_post);

            $comment = new Comment();
            $comment->setId($result[0]->comment_id);
            $comment->setContent($content);
            $comment->setPost($post);
            $comment->setCreated(new \DateTime($result[0]->comment_created_at));
            $comment->setUpdated(new \DateTime($result[0]->comment_updated_at));

            if ($result[0]->comment_deleted_at !== null) {
                $comment->setDeleted(new \DateTime($result[0]->comment_deleted_at));
            }

            return $comment;
        }

        return null;
    }

    public function findAllEntity(): array
    {
        // TODO: Implement findAllEntity() method.
    }


    /**
     * @param object $values
     * @return Entity
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function toEntity($values): Entity
    {
        /** @var ContentRepository $contentRepository */
        $contentRepository = new ContentRepository();

        $comment = new Comment();
        $comment->setId($values->id);


        $comment->setContent($contentRepository->find(Content::class, $values->id_content));
        $comment->setCreated(new \DateTime($values->created_at));
        $comment->setUpdated(new \DateTime($values->updated_at));

        if ($values->deleted_at !== null) {
            $comment->setDeleted(new \DateTime($values->deleted_at));
        }

        return $comment;
    }

    /**
     * @param Entity $comment
     * @return array
     * @throws \Exception
     */
    public static function buildUpdateExecuteParams(Entity $comment): array
    {
        /** @var Comment $comment */
        if ($comment->getContent()->getId() === null) {
            throw new \Exception('Missing content id');
        }

        return [
            'id' => $comment->getId(),
            'id_content' => $comment->getContent()->getId(),
            'id_post' => $comment->getPost()->getId(),
            'created_at' => $comment->getCreated()->format(EntityManager::DB_DEFAULT_DATE_FORMAT),
            'updated_at' => $comment->getUpdated()->format(EntityManager::DB_DEFAULT_DATE_FORMAT),
        ];
    }

    /**
     * @param Entity $post
     * @throws \Exception
     */
    public function deleteByPost(Entity $post): void {

        $sql = <<<EOT
        DELETE FROM comments WHERE id_post=:id;
EOT;
        $params = ['id' => $post->getId()];

        $this->em->prepare($sql);
        $this->em->execute($params);
    }
}