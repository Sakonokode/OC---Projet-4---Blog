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
    protected function insertEntity(Entity $entity): void
    {
        /** @var Content $entity */
        $annotation = $this->readEntityAnnotation($entity);

        $params = $entity->__toArray();

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);

        $entity->setId($this->em->getLastInsertedId());
    }

    /**
     * @param Entity $entity
     * @return array
     */
    protected static function buildExecuteParams(Entity $entity): array
    {
        /** @var Content $entity */
        return [
           'author' => $entity->getAuthor(),
           'content' => $entity->getContent(),
        ];
    }
}