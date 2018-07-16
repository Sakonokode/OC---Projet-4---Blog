<?php require_once __DIR__ . '/../views/header.php'; ?>

<?php

if(!empty($_POST)){
    $errors = array();

    if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])){
        $errors['username'] = "Vous pseudo n'est pas valide";
        debug($errors);
    }
    if(empty($_POST['email']) || filter_var($POST['email'], FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "Votre email n'est pas valide";
        debug($errors);
    }
    if(empty($_POST['password']) || $_POST['password'] != $POST['password-confirm']){
        $errors['password'] = "Vous devez entrer un mot de passe valide";
        debug($errors);
    }
}

?>

<?php require_once __DIR__ . '/../views/register-form.php'; ?>
<?php require_once __DIR__ . '/../views/footer.php'; ?>
