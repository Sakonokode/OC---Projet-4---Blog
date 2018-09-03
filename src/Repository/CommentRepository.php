<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 30/08/18
 * Time: 13:50
 */

namespace App\Repository;


use App\Entity\Comment;
use App\Entity\Entity;
use App\Entity\Post;

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

    public function updateEntity(Entity $entity): void
    {
        // TODO: Implement updateEntity() method.
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
        DELETE FROM $annotation->table WHERE id=:id;
EOT;

        $repository->deleteEntity($comment->getContent());
        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    public function findEntity(int $id): ?Entity
    {
        // TODO: Implement findEntity() method.
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