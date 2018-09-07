<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 07/09/18
 * Time: 12:59
 */

namespace App\Repository;


use App\Core\Session\Session;
use App\Entity\Content;
use App\Entity\Entity;
use App\Entity\Report;

/**
 * Class ReportRepository
 * @package App\Repository
 */
class ReportRepository extends Repository
{

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insertEntity(Entity $entity): void
    {
        /** @var Report $entity */
        $annotation = $this->readEntityAnnotation($entity);

        $params = ['id_user' => $entity->getUser()->getId(),
            'deleted_at' => $entity->getDeleted(),
        ];

        $this->em->prepare($annotation->insert);
        $this->em->execute($params);
        $entity->setId($this->em->getLastInsertedId());
    }

    /**
     * @param Entity $entity
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteEntity(Entity $entity): void
    {
        /** @var Report $entity */
        $annotation = $this->readEntityAnnotation($entity);
        $params =  ['id' => $entity->getId()];

        $sql = <<<EOT
        DELETE FROM $annotation->table WHERE id = :id;
EOT;

        $this->em->prepare($sql);
        $this->em->execute($params);
    }

    /**
     * @param Report $report
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function insertReport(Report $report): void
    {
        /** @var Report $report */
        $annotation = $this->readEntityAnnotation($report);

        $params = ['id_user' => Session::getInstance()->getUser()->getId(),
            'id_comment' => $report->getComment()->getId(),
            'id_report' => $report->getId(),
        ];

        $this->em->beginTransaction();
        $this->em->prepare($annotation->insertInCommentReports);
        $this->em->execute($params);
        $this->em->commit();
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function findCommentReports(int $id): ?array {

        $sql = <<<EOT
        SELECT * FROM comments_reports AS c_r  LEFT JOIN reports AS r ON r.id = c_r.id_report WHERE id_comment=$id
EOT;

        $reports = [];
        $this->em->query($sql);
        $results = $this->em->fetchAll();
        if (!empty($results)) {

            foreach ($results as $values) {
                dump($results);
                $reports[] = self::toEntity($values);
            }
        }

        return $reports;
    }

    /**
     * @param array $values
     * @return Entity
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function toEntity($values): Entity
    {
        /** @var CommentRepository $commentRepository */
        $commentRepository = new CommentRepository();

        /** @var ReportRepository $reportRepository */
        $reportRepository = new ReportRepository();

        $report = new Report();
        $report->setId($values->id);
        $report->setUser(Session::getInstance()->getUser());
        $report->setPost();
        $report->setReports();


        return $report;
    }

    public function updateEntity(Entity $entity): void
    {
        // TODO: Implement deleteEntity() method.
    }

    public function findEntity(int $id): ?Entity
    {

    }

    public function findAllEntity(): array
    {
        // TODO: Implement findAllEntity() method.
    }

    public static function buildInsertExecuteParams(Entity $entity): array
    {
        // TODO: Implement buildInsertExecuteParams() method.
    }
}