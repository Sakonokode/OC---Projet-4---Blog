<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 23/08/18
 * Time: 19:41
 */

namespace App\Repository;


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
     */
    protected function insertEntity(Entity $entity): void
    {
        /** @var Post $entity */
        $content = $entity->getContent();

    }
}