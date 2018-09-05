<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 16/07/18
 * Time: 19:06
 */

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\ContentRepository;
use App\Repository\PostRepository;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

class PostsController extends AbstractController
{
    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showPostAction(int $id) {
        $repository = new PostRepository();
        $post = $repository->find(Post::class, $id);

        echo $this->render('/blog/post_show.html.twig', ['post' => $post]);
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listPostsAction() {
        $repository = new PostRepository();
        $posts = $repository->findAll(Post::class);

        echo $this->render('/blog/index.html.twig', ['user' => $_SESSION['user'], 'posts' => $posts]);
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function newPostAction() {
        echo $this->render('/user/new_post.html.twig', []);
    }

    /**
     * @throws \Exception
     */
    public function checkNewPostAction() {

        if ($_SESSION['user'] === null) {
            throw new \Exception("You have to login before creating a post");
        }
        if ($_POST['_title'] === null) {
            throw new \Exception("To create a new post, please write a title");
        }

        if ($_POST['_content'] === null) {
            throw new \Exception("To create a new post, please write some content");
        }

        /** @var PostRepository $repository */
        $repository = new PostRepository();

        /** @var User $authenticated */
        $authenticated = $_SESSION['user'];

        /** @var Content $content */
        $content = new Content();
        $content->setAuthor($authenticated);
        $content->setContent($_POST['_content']);

        /** @var Post $post */
        $post = new Post();
        $post->setTitle($_POST['_title']);
        $post->setDescription($_POST['_description']);
        $post->setSlug($post->slugify($post->getDescription()));
        $post->setContent($content);

        $repository->insert($post);
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
    public function editPostAction(int $id) {

        /** @var PostRepository $repository */
        $repository = new PostRepository();

        /** @var Post $post */
        $post = $repository->find(Post::class, $id);

        /** @var User $authenticated */
        $authenticated = $_SESSION['user'];
        if ($authenticated->getId() == $post->getContent()->getAuthor()->getId()) {
            echo $this->render('/user/edit_post.html.twig', ['post' => $post]);
        }else {
            throw new \Exception("You can not edit a Post you have not created");
        }
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
    public function deletePostAction(int $id) {
        /** @var PostRepository $repository */
        $repository = new PostRepository();

        /** @var Post $post */
        $post = $repository->find(Post::class, $id);
        if ($_SESSION['id'] == $post->getContent()->getAuthor()->getId()) {
            echo $this->render('/user/delete_post.html.twig', ['post' => $post]);
        }else {
            throw new \Exception("You can not delete a Post you have not created");
        }
    }

    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function checkDeletePostAction(int $id) {
        /** @var PostRepository $repository */
        $repository = new PostRepository();

        /** @var Post $post */
        $post = $repository->find(Post::class, $id);
        if ($_SESSION['id'] == $post->getContent()->getAuthor()->getId()) {
            $repository->delete($post);
        }else {
            throw new \Exception("You can not delete a Post you have not created");
        }

    }

    /**
     * @param int $id
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function checkEditPostAction(int $id) {
        /** @var PostRepository $postRepository */
        $postRepository = new PostRepository();

        /** @var Post $post */
        $post = $postRepository->find(Post::class, $id);
        if ($this->user->getId() == $post->getContent()->getAuthor()->getId()) {
            $post->getContent()->setContent($_POST['_content']);
            /** @var Post $updatedPost */
            $post->setTitle($_POST['_title']);
            $post->setDescription($_POST['_description']);
            $post->setSlug($post->slugify($_POST['_title']));
            $post->setUpdated(new \DateTime());
            $postRepository->update($post);
        }else {
            throw new \Exception("You can not edit a Post you have not created");
        }
    }

}