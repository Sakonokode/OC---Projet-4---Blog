<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Annotations\AnnotationRegistry;

ini_set('display_errors', 1);
require_once "vendor/autoload.php";
session_start();
# To avoid DocParser error, 'class not found'
AnnotationRegistry::registerLoader('class_exists');
require_once __DIR__ . '/src/Core/Router/routes.php';


try{

    $postRepository = new PostRepository();
    $commentRepository = new CommentRepository();
    $userRepository = new UserRepository();

    $user = new User();
    $user->setNickname('John Doe');
    $user->setEmail('johndoe@domain.com');
    $user->setPassword('password');
    #$user->setId(2);

    $content = new Content();
    $content->setContent('test');
    $content->setAuthor($user);
    #$content->setId(6);

    $post = new Post();
    $post->setTitle('titre test');
    $post->setDescription('description test');
    $post->setSlug('titre-test');
    $post->setContent($content);
    #$post->setId(6);

    $comment = new Comment();
    $comment->setContent($content);
    $comment->setPost($post);

    // INSERT USER & POST & COMMENT
    $userRepository->insert($user);
    $postRepository->insert($post);
    $commentRepository->insert($comment);

    // DELETE POST & USER & COMMENT
    #$commentRepository->delete($comment);
    #$postRepository->delete($post);
    #$userRepository->delete($user);


    // FIND POST & USER
    #$post = $postRepository->find(Post::class, 1);
    #$user = $userRepository->findEntity(1);

    /* SET NEW SETTINGS TO TEST UPDATE FUNC
    $user->setNickname('new_John Doe');
    $user->setEmail('new_johndoe@domain.com');
    $user->setPassword('new_password');
    $user->setRole(2);

    $content->setContent('updated test');
    $content->setAuthor($user);

    $post->setTitle('updated titre test');
    $post->setDescription('updated description test');
    $post->setSlug('updated-title-test');
    $post->setContent($content);

    */
    // UPDATE USER & POST
    #$userRepository->updateEntity($user);
    #$postRepository->update($post);




    throw new \Exception("toto");

} catch (\Exception $e) {
    echo '<pre>';
    echo sprintf('Exception - file<br/> %s,<br/> line %d, <br/>message %s, <br/>Stack Trace,<br/> %s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
    echo '</pre>';
    exit("error in index.php");
}

