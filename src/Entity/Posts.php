<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 12:36
 */

namespace App\Entity;

use App\Annotations\EntityAnnotation as EntityAnnotation;
use App\Entity\Entity as Entity;
use App\Traits\Postable;

/**
 * Class Posts
 * @package App\Entity
 * @EntityAnnotation(
 *     table="posts",
 *     insert="INSERT INTO posts VALUES(NULL, ?, ?, ?, NULL, DATE('NOW'), DATE('NOW'), NULL);",
 *     get="SELECT * FROM posts WHERE id=%d;",
 *     update="",
 *     delete=""
 * )
 */
class Posts extends Entity
{
    use Postable;

    /** @var null|string $title */
    protected $title;

    /** @var string $description */
    protected $description;

    /** @var string $slug */
    protected $slug;

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
    public function getDescription(): string
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
    public function getSlug(): string
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
     * @param array $objectAsArray
     * @return Posts
     */
    public static function instantiate(array $objectAsArray): Entity
    {
        $post = new self();
        $post->setId($objectAsArray['id']);
        $post->settitle($objectAsArray['title']);
        $post->setDescription($objectAsArray['description']);
        $post->setSlug($objectAsArray['slug']);

        return $post;
    }

    /**
     * @return array
     */
    public function __toArray(): array
    {
        return [
            'id' => $this->id,
            'id_postable' => $this->idPostable,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'created_at' => $this->created,
            'updated_at' => $this->updated
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return (string)$this;
    }
}