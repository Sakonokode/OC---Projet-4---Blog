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
 *     insert="INSERT INTO comments VALUES(NULL, :id_content, :id_post, NOW(), NOW(), :deleted_at);",
 *     update="UPDATE comments AS c SET c.id_content = :id_content, c.id_post = :id_post, c.created_at = :created_at, c.updated_at = :updated_at WHERE c.id = :id;",
 *     getReports="SELECT * FROM comments_reports AS c_r  LEFT JOIN repports AS r ON r.id = c_r.id_repport WHERE id_comment=:id",
 *     repository="CommentRepository"
 * )
 */
class Comment extends Entity
{

    /** @var array $reports */
    protected $reports = [];

    /** @var Content $content */
    protected $content;

    /** @var Post $post */
    protected $post;

    /**
     * @return array
     */
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

    /**
     * @param $reports
     * @return $this
     */
    public function setReports($reports)
    {

        $this->reports = $reports;

        return $this;
    }

}