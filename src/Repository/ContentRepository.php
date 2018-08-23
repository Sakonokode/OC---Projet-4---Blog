<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 23/08/18
 * Time: 22:00
 */

namespace App\Repository;


use App\Entity\Entity;

class ContentRepository extends Repository
{
    /**
     * ContentRepository constructor.
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
        // TODO: Implement insertEntity() method.
    }
}