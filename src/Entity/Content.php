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
 *     insert="INSERT INTO content VALUES(NULL, :author, :content, NOW(), NOW(), NOW());",
 *     update="UPDATE content AS c SET  c.content = ':content' WHERE c.id = ':id';",
 *     delete="DELETE FROM content WHERE id=:id;",
 *     hasContent=false,
 *     repository="ContentRepository"
 * )
 */
class Content extends Entity
{
    /** @var User $author */
    protected $author;

    /** @var string $content */
    protected $content;

    /**
     * Content constructor.
     */
    public function __construct()
    {
        $this->author = new User();
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
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