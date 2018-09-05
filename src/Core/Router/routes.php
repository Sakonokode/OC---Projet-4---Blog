<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:37
 */

use App\Core\Router\Router;

$router = new Router($_SERVER['REQUEST_URI']);

$router->get('/', "Home#homeAction");
$router->get('/posts', "Posts#listPostsAction");
$router->get('/posts/new-post/', "Posts#newPostAction");
$router->post('/posts/new-post/', "Posts#checkNewPostAction");
$router->get('/posts/:id', "Posts#showPostAction");
$router->get('/subscribe/', "Security#subscribeAction");
$router->post('/subscribe/', "Security#checkSubscribtionAction");
$router->get('/login/', "Security#authAction");
$router->post('/login/', "Security#checkAuthAction");
$router->get('/logout/', "Security#logoutAction");
$router->get('/posts/edit-post/:id', "Posts#editPostAction");
$router->post('/posts/edit-post/:id', "Posts#checkEditPostAction");
$router->get('/posts/delete-post/:id', "Posts#deletePostAction");
$router->post('/posts/delete-post/:id', "Posts#checkDeletePostAction");


$router->run();

// Ecrire le fichier .htaccess a la racine pour rediriger toutes les requetes http entrantes vers index.php qui pourra recuperer l'url avec les / grace a
// $_SERVER["REQUEST_URI"] (Url demandee par l'user)

// Verifier que le fichier du controlleur existe dans le dossier controlleur, en fonction du premier parametre de l'url
// Verifier que le controlleur demande,  et l'action demandee existent
// On appel le controlleur (et ses potentiels parametres)
// Pour appeller la fonction correspondant a l action demandee, on utilise call_user_func_array.