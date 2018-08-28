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

    $repository = new PostRepository();

    $userRepository = new UserRepository();
    /** @var User $user */
    $user = $userRepository->findEntity(1);

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


    $repository->insert($post);
    #dump("POST SUCCESSFULLY ADDED TO DATABASE\n");

    #$repository->delete($post);
    #dump("POST SUCCESSFULLY DELETED FROM DATABASE\n");

    $post = $repository->find(Post::class, 1);
    dump($post);


    throw new \Exception("toto");

} catch (\Exception $e) {
    echo '<pre>';
    echo sprintf('Exception - file<br/> %s,<br/> line %d, <br/>message %s, <br/>Stack Trace,<br/> %s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
    echo '</pre>';
    exit("error in index.php");
}

