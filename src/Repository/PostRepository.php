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
    protected function insertEntity(Entity $entity): void
    {
        /** @var Post $entity */
        $content = $entity->getContent();
        $annotation = $this->readEntityAnnotation($entity);

        $this->insert($content);
        $params = self::buildExecuteParams($entity);
        dump($params);
        dump($annotation->insert);

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);
        $entity->setId($this->em->getLastInsertedId());

        /* MON CODE
        $repository = new ContentRepository();
        /** @var Content $content */
        /*
        $content = new Content();
        $annotation = $this->readEntityAnnotation($content);
        $contentSql = $annotation->insert;
        $repository->insertEntity($content, $contentSql);

        /** @var Post $entity */
        /*
        $entity->setIdContent($content->getId());
        /** @var Post $entity */
        /*
        $params = $entity->__toArray();
        unset($params['content']);

        $this->em->prepare($sql);
        $this->em->execute($params);

        $entity->setId($this->em->getLastInsertedId());

        dump($entity);
        */
    }

    /**
     * @param Entity $post
     * @return array
     * @throws \Exception
     */
    protected static function buildExecuteParams(Entity $post): array
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
}