<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 01/08/18
 * Time: 20:07
 */

namespace App\Entity;

use App\Traits\Timestampable;

/**
 * Class Entity
 * @package App\Entity
 */
abstract class Entity
{
    use Timestampable;

    /** @var int $id */
    protected $id;

    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}