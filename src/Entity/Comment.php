<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 25/07/18
 * Time: 13:11
 */

namespace App\Entity;

use App\Annotations\EntityAnnotation;

/**
 * Class Comments
 * @package App\Entity
 * @EntityAnnotation(
 *     table="comments",
 *     insert="INSERT INTO comments VALUES(NULL, :id_content, :id_post, NOW(), NOW(), NOW());",
 *     find="SELECT * FROM comments WHERE id=%d;",
 *     update="",
 *     delete="",
 *     hasContent=true,
 *     repository="CommentRepository"
 * )
 */
class Comment extends Entity
{

    protected $reports;

    /** @var Content $content */
    protected $content;

    /** @var Post $post */
    protected $post;

    public function getReports()
    {

        return $this->reports;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
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

    public function setReports($reports)
    {

        $this->reports = $reports;

        return $this;
    }

}