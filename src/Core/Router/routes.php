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
$router->get('/posts/:id', "Posts#showPostsAction", function($id){ echo 'Afficher le billet' . $id; });
$router->post('/posts/:id', "Posts#newAction", function($id){ echo 'Poster un billet' . $id;});

$router->run();

// Ecrire le fichier .htaccess a la racine pour rediriger toutes les requetes http entrantes vers index.php qui pourra recuperer l'url avec les / grace a
// $_SERVER["REQUEST_URI"] (Url demandee par l'user)

// Verifier que le fichier du controlleur existe dans le dossier controlleur, en fonction du premier parametre de l'url
// Verifier que le controlleur demande,  et l'action demandee existent
// On appel le controlleur (et ses potentiels parametres)
// Pour appeller la fonction correspondant a l action demandee, on utilise call_user_func_array.