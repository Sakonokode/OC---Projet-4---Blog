<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 13:27
 */

namespace App\Entity;


use App\Annotations\EntityAnnotation;

/**
 * Class Content
 * @package App\Entity
 * @EntityAnnotation(
 *     table="content",
 *     insert="INSERT INTO content VALUES(id, author, content, created_at, updated_at, deleted_at)",
 *     get="",
 *     update="",
 *     delete=""
 * )
 */
class Content extends Entity {

    /** @var string $author */
    protected $author;

    /** @var string $content */
    protected $content;

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

    public function __toArray(): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'content' => $this->content,
            'created_at' => $this->created,
            'updated_at' => $this->updated,
            'deleted_at' => $this->deleted
        ];
    }
}