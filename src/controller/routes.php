<?php
/**
 * Created by PhpStorm.
 * User: coltluger
 * Date: 13/07/18
 * Time: 17:37
 */

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'listPosts') {
        //On appel le controlleur qui s'occupe de renvoyer la liste des posts
        echo ("test de condition remplie");
    }
    elseif ($_GET['action'] == 'readPost') {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            //On appel le controlleur qui renvoie le post demande par l user
            echo ("test de condition remplie");

        }
    }
}


// Ecrire le fichier .htaccess a la racine pour rediriger toutes les requetes http entrantes vers index.php qui pourra recuperer l'url avec les / grace a
// $_SERVER["REQUEST_URI"] (Url demandee par l'user)

// Verifier que le fichier du controlleur existe dans le dossier controlleur, en fonction du premier parametre de l'url
// Verifier que le controlleur demande,  et l'action demandee existent
// On appel le controlleur (et ses potentiels parametres)
// Pour appeller la fonction correspondant a l action demandee, on utilise call_user_func_array.