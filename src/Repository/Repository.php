<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 23/08/18
 * Time: 19:41
 */

namespace App\Repository;


use App\Annotations\EntityAnnotation;
use App\Core\Database\EntityManager;
use App\Entity\Entity;
use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\Post;
use App\Entity\User;
use PDO;

abstract class Repository
{
    /** @var EntityManager $em */
    protected $em;

    /**
     * Repository constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        $this->em = new EntityManager();
    }

    /**
     * @param Entity $entity
     * @return EntityAnnotation
     * @throws \ReflectionException
     */
    protected function readEntityAnnotation(Entity $entity): EntityAnnotation
    {
        $className = get_class($entity);
        $reflectionClass = new \ReflectionClass($className);

        /** @var EntityAnnotation $annotation */
        return $this->em->getReader()->getClassAnnotations($reflectionClass)[0];
    }
    /**
     * @param Entity $entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insert(Entity $entity)
    {
        $annotation = $this->readEntityAnnotation($entity);
        $this->em->beginTransaction();

        $className = "App\\Repository\\" . $annotation->repository;
        /** @var Repository $repository */
        $repository = new $className();
        $repository->insertEntity($entity);

        $this->em->commit();
    }

    abstract protected function insertEntity(Entity $entity): void;

    /**
     * @param $className
     * @param $annotation
     * @throws \ReflectionException
     */
    private function insertByClassName($className, $annotation)
    {
        $sql = $annotation[0]->insert;
        $sql = sprintf($sql);

        switch ($className) {
            case Content::class:
                /** @var Content $entity */
                $params = $entity->__toArray();
                $this->em->prepare($sql);
                $this->em->execute($params);
                break;
            case Post::class:
                /** @var Post $entity */

                $this->insertByClassName($content, $annotation);
                $idContent = $this->em->getLastInsertedId($annotation[0]->table);
                $this->em->prepare($sql);

                $entity->setId($idContent);
                /** @var Post $entity */
                $params = $entity->__toArray();
                $this->em->execute($params);
                break;
            case User::class:
                break;
            case Comment::class:
                break;
        }
    }

    /**
     * @param string $entityClassName
     * @param int $id
     * @throws \ReflectionException
     * @return Entity
     */
    public function get(string $entityClassName, int $id): Entity
    {
        $params = [];

        $reflectionClass = new \ReflectionClass($entityClassName);

        $annotations = $this->reader->getClassAnnotations($reflectionClass);

        dump($annotations);

        $sql = $annotations[0]['get'];
        $sql = sprintf($sql, $id);

        dump($annotations[0]['table']);

        switch ($annotations[0]['table']) {
            case Post::class:
                /** @var Post $entity */
                $sql = sprintf($sql, $entity->getIdPostable());
                $params = $entity->__toArray();
                break;
            case User::class:
                /** @var User $entity */
                $sql = sprintf($sql, $entity->getId());
                $params = $entity->__toArray();
                break;
            case Comment::class:
                break;
        }
        dump($params);

        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute($params)) {
            throw new \Exception("error in get func");
        }

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $entity = Post::instantiate($result);

        return $entity;
    }
}