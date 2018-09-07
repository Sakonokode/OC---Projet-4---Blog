<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 07/09/18
 * Time: 14:18
 */

namespace App\Controller;

use App\Core\Session\Session;
use App\Entity\Comment;
use App\Entity\Report;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\ReportRepository;

/**
 * Class ReportsController
 * @package App\Controller
 */
class ReportsController extends AbstractController
{
    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function newReportAction(int $id) {
        if (Session::getInstance()->getUser() === null) {
            throw new \Exception("You have to login before creating a post");
        }

        /** @var ReportRepository $reportRepository */
        $reportRepository = new ReportRepository();

        /** @var CommentRepository $commentRepository */
        $commentRepository = new CommentRepository();

        /** @var Comment $comment */
        $comment = $commentRepository->findEntity($id);

        $reportsComment = $reportRepository->findCommentReports($id);

        /** @var Report $report */
        $report = new Report();
        $report->setUser(Session::getInstance()->getUser());

        $reportRepository->insert($report);

        $report->setPost($comment->getPost());
        $report->setComment($comment);
        $report->setReports($reportRepository->findCommentReports($id));

        if (Session::getInstance()->getUser()->getId() === $reportsComment) {
            throw new \Exception("You cannot report more than one a comment");
        }
        $reportRepository->insertReport($report);
        $this->redirect('show_post', ['id' => $comment->getPost()->getId()], 'GET');
    }

}