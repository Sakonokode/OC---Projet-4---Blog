<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 13:27
 */

namespace App\Entity;

abstract class Postable {

    protected $idPostable;
    protected $author;
    protected $content;

    public function getIdPostable() {

        return $this->idPostable;
    }

    public function setIdPostable($id) {

        $id = (int) $id;

        if ($id > 0) {

            $this->idPostable = $id;
        }

        return $this;
    }

    public function getAuthor() {

        return $this->author;
    }

    public function setAuthor($author)
    {

        if (is_string($author)) {

            $this->author = $author;
        }

        return $this;
    }

    public function getContent() {

        return $this->content;
    }

    public function setContent($content) {

        $this->content = $content;

        return $this;
    }
}