<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 07/09/18
 * Time: 12:42
 */

namespace App\Entity;

use App\Annotations\EntityAnnotation;

/**
 * Class Report
 * @package App\Entity
 * @EntityAnnotation(
 *     table="reports",
 *     insert="INSERT INTO reports VALUES (NULL, :id_user, NOW(), NOW(), :deleted_at);",
 *     insertInCommentReports="INSERT INTO comments_reports VALUES (NULL, :id_user, :id_comment, :id_report)",
 *     repository="ReportRepository"
 * )
 */
class Report extends Entity
{
    /** int $report */
    protected $reports;

    /** @var Comment $comment */
    protected $comment;

    /** @var User $user */
    protected $user;

    /** @var Post $post */
    protected $post;

    /**
     * @return mixed
     */
    public function getReports()
    {
        return $this->reports;
    }

    /**
     * @param mixed $reports
     */
    public function setReports($reports): void
    {
        $this->reports = $reports;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }

    /**
     * @param Comment $comment
     */
    public function setComment(Comment $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
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

}