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
 *     insert="INSERT INTO comments VALUES(id, id_content, title, description, 'slug', NOW(), NOW(), NOW());",
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


    public function getReports()
    {

        return $this->reports;
    }

    public function setReports($reports)
    {

        $this->reports = $reports;

        return $this;
    }

    /**
     * @param array $objectAsArray
     * @return Entity
     */
    public static function instantiate(array $objectAsArray): Entity
    {
        $comment = new self();
        $comment->setReports($objectAsArray['reports']);

        return $comment;
    }

}