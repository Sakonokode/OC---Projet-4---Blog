<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 12:36
 */

namespace App\Entity;


class Posts extends Postable {

    protected $title;
    protected $comments;
    protected $description;
    protected $reports;
    protected $slug;

    public function getTitle() {

        return $this->title;
    }

    public function setTitle($title) {

        if (is_string($title)) {

            $this->title = $title;
        }

        return $this;
    }

    public function getComments() {

        return $this->comments;
    }

    public function setComments($comments) {

        $this->comments = $comments;

        return $this;
    }

    public function getDescription() {

        return $this->description;
    }

    public function setDescription($description) {

        $this->description = $description;

        return $this;
    }

    public function getReports() {

        return $this->reports;
    }

    public function setReports($reports) {

        $this->reports = $reports;

        return $this;
    }

    public function getSlug() {

        return $this->slug;
    }

    public function setSlug($slug) {

        $this->slug = $slug;

        return $this;
    }

}