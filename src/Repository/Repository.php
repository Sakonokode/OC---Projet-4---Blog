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
        $this->em = EntityManager::getInstance();
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
    public function insert(Entity $entity): void
    {
        $annotation = $this->readEntityAnnotation($entity);
        $className = "App\\Repository\\" . $annotation->repository;

        $this->em->beginTransaction();
        /** @var Repository $repository */
        $repository = new $className();
        $repository->insertEntity($entity);

        $this->em->commit();
    }

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function delete(Entity $entity): void
    {
        $annotation = $this->readEntityAnnotation($entity);
        $className = "App\\Repository\\" . $annotation->repository;

        $this->em->beginTransaction();

        /** @var Repository $repository */
        $repository = new $className();
        $repository->deleteEntity($entity);

        $this->em->commit();
    }

    /**
     * @param string $className
     * @param int $id
     * @return Entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function find(string $className, int $id): ?Entity
    {
        $entity = new $className();
        $annotation = $this->readEntityAnnotation($entity);
        $repositoryClassName = "App\\Repository\\" . $annotation->repository;

        /** @var Repository $repository */
        $repository = new $repositoryClassName();
        $entity = $repository->findEntity($id);

        return $entity;
    }

    /**
     * @param string $className
     * @return array
     * @throws \ReflectionException
     */
    public function findAll(string $className): array
    {
        $entity = new $className();
        $annotation = $this->readEntityAnnotation($entity);
        $repositoryClassName = "App\\Repository\\" . $annotation->repository;

        /** @var Repository $repository */
        $repository = new $repositoryClassName();
        return $repository->findAllEntity();
    }

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     */
    public function update(Entity $entity): void
    {
        $annotation = $this->readEntityAnnotation($entity);
        $className = "App\\Repository\\" . $annotation->repository;

        $this->em->beginTransaction();

        /** @var Repository $repository */
        $repository = new $className();
        $repository->updateEntity($entity);

        $this->em->commit();
    }

    abstract public function insertEntity(Entity $entity): void;

    abstract public function updateEntity(Entity $entity): void;

    abstract public static function buildInsertExecuteParams(Entity $entity): array;

    abstract public function deleteEntity(Entity $entity): void;

    abstract public function findEntity(int $id): ?Entity;

    abstract public function findAllEntity(): array;

    abstract public function toEntity(array $values): Entity;
}