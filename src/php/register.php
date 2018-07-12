<?php require 'includes/header.php'; ?>

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

<h1>S'inscrire</h1>

<form action="" method="POST">
    <div class="form-group">
        <label for="">Pseudo</label>
        <input type="text" name="username" class="form-control" required/>
    </div>

    <div class="form-group">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control" required/>
    </div>

    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="text" name="password" class="form-control" required/>
    </div>

    <div class="form-group">
        <label for="">Confirmez votre mot de passe</label>
        <input type="text" name="password_confirm" class="form-control" required/>
    </div>

    <button type="submit" class="btn btn-primary">M'inscrire</button>
</form>


<?php require 'includes/footer.php'; ?>
