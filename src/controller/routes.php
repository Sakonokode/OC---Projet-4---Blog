<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:37
 */

namespace App;

$router = new App;


// Ecrire le fichier .htaccess a la racine pour rediriger toutes les requetes http entrantes vers index.php qui pourra recuperer l'url avec les / grace a
// $_SERVER["REQUEST_URI"] (Url demandee par l'user)

// Verifier que le fichier du controlleur existe dans le dossier controlleur, en fonction du premier parametre de l'url
// Verifier que le controlleur demande,  et l'action demandee existent
// On appel le controlleur (et ses potentiels parametres)
// Pour appeller la fonction correspondant a l action demandee, on utilise call_user_func_array.