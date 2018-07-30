<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 13:11
 */

namespace App\Entity;


class Comments extends Postable {

    protected $reports;


    public function getReports() {

        return $this->reports;
    }

    public function setReports($reports) {

        $this->reports = $reports;

        return $this;
    }
}