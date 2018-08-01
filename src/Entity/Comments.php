<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 13:11
 */

namespace App\Entity;

use App\Annotation\EntityAnnotation;

/**
 * Class Comments
 * @package App\Entity
 * @EntityAnnotation(
 *     table = "comments"
 * )
 */
class Comments extends Entity {

    protected $reports;


    public function getReports() {

        return $this->reports;
    }

    public function setReports($reports) {

        $this->reports = $reports;

        return $this;
    }
}