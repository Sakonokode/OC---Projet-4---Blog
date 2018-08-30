<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

use App\Entity\Content;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Annotations\AnnotationRegistry;

ini_set('display_errors', 1);
require_once "vendor/autoload.php";
require_once __DIR__ . '/src/Core/Router/routes.php';
session_start();
AnnotationRegistry::registerLoader('class_exists');

try{

    $postRepository = new PostRepository();

    $userRepository = new UserRepository();
    #$user = $userRepository->findEntity(1);


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

    $userRepository->insert($user);
    $postRepository->insert($post);
    #dump("POST SUCCESSFULLY ADDED TO DATABASE\n");

    #$postRepository->delete($post);
    #dump("POST SUCCESSFULLY DELETED FROM DATABASE\n");

    #$post = $postRepository->find(Post::class, 1);
    #dump($post);

    #$userRepository->delete($user);
    #dump('USER SUCCESSFULLY DELETED FROM DATABASE');

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

    #$userRepository->updateEntity($user);
    #dump($user, 'USER SUCCESFFULLY UPDATED FROM DATABASE');

    $postRepository->update($post);
    dump($post);




    throw new \Exception("toto");

} catch (\Exception $e) {
    echo '<pre>';
    echo sprintf('Exception - file<br/> %s,<br/> line %d, <br/>message %s, <br/>Stack Trace,<br/> %s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
    echo '</pre>';
    exit("error in index.php");
}

