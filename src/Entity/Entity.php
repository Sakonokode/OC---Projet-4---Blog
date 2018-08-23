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

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function __toArray(): array
    {
        $array   = [];
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            if (is_a((object)$this->{$prop->getName()}, Entity::class)) {
                $array[$prop->getName()] = $this->{$prop->getName()}->__toArray();
                continue;
            }

            $array[$prop->getName()] = $this->{$prop->getName()};
        }

        return $array;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %d', self::class, $this->id);
    }
}