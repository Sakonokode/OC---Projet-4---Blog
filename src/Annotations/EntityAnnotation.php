<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 01/08/18
 * Time: 19:58
 */

namespace App\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Target("CLASS")
 */
class EntityAnnotation
{
    /** @var string $table */
    public $table;

    /** @var string $insert */
    public $insert;

    /** @var string $get */
    public $get;

    /** @var string $update */
    public $update;

    /** @var string $delete */
    public $delete;

    /** @var bool $hasContent */
    public $hasContent = false;

    /**
     * @Annotation\Required()
     * @var string $repository
     */
    public $repository = null;
}