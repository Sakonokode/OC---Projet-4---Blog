<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 13:27
 */

namespace App\Traits;

trait Postable {

    /** @var int $idPostable */
    protected $idPostable;

    /** @var string $author */
    protected $author;

    /** @var string $content */
    protected $content;

    /**
     * @return int
     */
    public function getIdPostable(): int
    {
        return $this->idPostable;
    }

    /**
     * @param int $idPostable
     */
    public function setIdPostable(int $idPostable): void
    {
        $this->idPostable = $idPostable;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


}