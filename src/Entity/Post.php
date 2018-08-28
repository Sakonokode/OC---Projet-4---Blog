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
 *     insert="INSERT INTO posts VALUES(NULL, :id_content, :title, :description, :slug, NOW(), NOW(), NOW());",
 *     update="UPDATE posts SET title=:title, description=:description, slug=:slug, NOW()=:updated_at WHERE id=:id;",
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

}