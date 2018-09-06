<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 12:36
 */

namespace App\Entity;

use App\Annotations\EntityAnnotation;
use App\Entity\Entity as Entity;

/**
 * Class Posts
 * @package App\Entity
 * @EntityAnnotation(
 *     table="posts",
 *     insert="INSERT INTO posts VALUES(NULL, :id_content, :title, :description, :slug, NOW(), NOW(), :deleted_at);",
 *     update="UPDATE posts AS p SET  p.title = :title, p.description = :description, p.slug = :slug, p.updated_at = :updated_at WHERE p.id = :id;",
 *     delete="DELETE FROM posts WHERE id=:id;",
 *     hasContent=true,
 *     repository="PostRepository"
 * )
 */
class Post extends Entity
{
    /** @var null|string $title */
    protected $title;

    /** @var string $description */
    protected $description;

    /** @var null|string $slug */
    protected $slug;

    /** @var Content $content */
    protected $content;

    /** @var null|array $comments */
    protected $comments = [];

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->content = new Content();
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getComments(): ?array
    {
        return $this->comments;
    }

    /**
     * @param null|array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }



    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @param Content $content
     */
    public function setContent(Content $content): void
    {
        $this->content = $content;
    }

    /**
     * @param $text
     * @return null|string|string[]
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}