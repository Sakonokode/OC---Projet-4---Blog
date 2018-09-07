<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:36
 */

use App\Core\Router\Router;
use App\Core\Session\Session;
use Doctrine\Common\Annotations\AnnotationRegistry;

ini_set('display_errors', 1);
require_once "vendor/autoload.php";
Session::start();
# To avoid DocParser error, 'class not found'
AnnotationRegistry::registerLoader('class_exists');

/** @var Router $router */
$router = new Router($_SERVER['REQUEST_URI']);

$router->route('home', 'get', '/home', "Home#homeAction");

$router->route('get_form_subscribe','get', '/subscribe',"Security#subscribeAction");
$router->route('submit_form_subscribe', 'post', '/subscribe', "Security#checkSubscribtionAction");
$router->route('get_form_login','get', '/login', "Security#authAction");
$router->route('submit_form_login', 'post', '/login', "Security#checkAuthAction");
$router->route('logout', 'get', '/logout', "Security#logoutAction");

$router->route('show_post', 'get', '/posts/:id', "Posts#showPostAction");
$router->route('get_form_edit_post', 'get', '/posts/edit/:id', "Posts#getFormEditPostAction");
$router->route('submit_form_edit_post', 'post', '/posts/edit/:id', "Posts#editPostAction");
$router->route('delete_post', 'get', '/posts/delete/:id', "Posts#deletePostAction");
$router->route('submit_form_new_post', 'post', '/posts-new', "Posts#newPostAction");
$router->route('get_form_new_post', 'get', '/posts-new', "Posts#getFormNewPostAction");
$router->route('list_posts', 'get', '/posts', "Posts#listPostsAction");

$router->route('get_form_new_comment', 'get', '/comment/post/:id', "Comments#getFormNewCommentAction");
$router->route('submit_form_new_comment', 'post', '/comment/post/:id', "Comments#newCommentAction");
$router->route('get_form_edit_comment', 'get', '/comments/edit/:id', "Comments#getFormEditCommentAction");
$router->route('submit_form_edit_comment', 'post', '/comments/edit/:id', "Comments#editCommentAction");
$router->route('submit_delete_comment', 'get', '/comments/delete/:id', "Comments#deleteCommentAction");

$router->route('submit_report', 'get', '/report/comment/:id', "Reports#newReportAction");
$router->route('delete_report', 'get', '/delete/report/comment/:id', "Reports#deleteReportAction");

$router->run();

Session::getInstance()->setRouter($router);