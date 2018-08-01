<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 01/08/18
 * Time: 19:58
 */

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class EntityAnnotation
{
    /** @var string $table */
    public $table;

    /** @var string $insert */
    public $insert;
}