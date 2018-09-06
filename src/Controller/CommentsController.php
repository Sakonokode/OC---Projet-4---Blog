<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 06/09/18
 * Time: 11:42
 */

namespace App\Controller;


use App\Core\Session\Session;
use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;

class CommentsController extends AbstractController
{
    /**
     * @param int $postId
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function getFormNewCommentAction(int $postId) {

        if (Session::getInstance()->getUser() === null) {
            throw new \Exception("You can not create a comment if you are not logged");
        }

        echo $this->render('/user/new_comment.html.twig', ['post_id' => $postId]);
    }

    /**
     * @param int $postId
     * @throws \Exception
     */
    public function newCommentAction(int $postId) {

        if (Session::getInstance()->getUser() === null) {
            throw new \Exception("You have to login before creating a post");
        }
        if ($_POST['_content'] === null) {
            throw new \Exception("To create a new post, please write some content");
        }

        /** @var CommentRepository $commentRepository */
        $commentRepository = new CommentRepository();

        /** @var PostRepository $postRepository */
        $postRepository = new PostRepository();

        /** @var Content $content */
        $content = new Content();
        $content->setAuthor(Session::getInstance()->getUser());
        $content->setContent($_POST['_content']);

        /** @var Comment $comment */
        $comment = new Comment();
        $comment->setPost($postRepository->find(Post::class, $postId));
        $comment->setContent($content);

        $commentRepository->insert($comment);

        $this->redirect('show_post', ['id' => $comment->getId()], 'GET');
    }

    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function getFormEditCommentAction(int $id) {

        /** @var CommentRepository $commentRepository */
        $commentRepository = new CommentRepository();

        /** @var Comment $comment */
        $comment = $commentRepository->find(Comment::class, $id);

        if (Session::getInstance()->getUser()->getId() !== $comment->getContent()->getAuthor()->getId()) {
            throw new \Exception("You can not edit a Comment you have not created");
        }

        echo $this->render('/user/edit_comment.html.twig', ['comment' => $comment]);
    }

    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function editCommentAction(int $id) {

        /** @var CommentRepository $repository */
        $commentRepository = new CommentRepository();

        /** @var Comment $comment */
        $comment = $commentRepository->find(Comment::class, $id);
        if (Session::getInstance()->getUser()->getId() == $comment->getContent()->getAuthor()->getId()) {

            /** @var Content $content */
            $content = new Content();
            $content->setContent($_POST['_content']);
            $content->setId($comment->getContent()->getId());
            $comment->setContent($content);

            $commentRepository->update($comment);
        }else {
            throw new \Exception("You can not edit a Post you have not created");
        }
    }

    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function deleteCommentAction(int $id) {

        /** @var CommentRepository $repository */
        $repository = new CommentRepository();

        /** @var Comment $comment */
        $comment = $repository->find(Comment::class, $id);

        if (Session::getInstance()->getUser()->getId() !== $comment->getContent()->getAuthor()->getId()) {
            throw new \Exception("You can not delete a Comment you have not created");
        }

        $idPost = $comment->getPost()->getId();
        $repository->delete($comment);

        Session::getInstance()->getRouter()->redirect('show_post', ['id' => $idPost], 'GET');
    }
}