<?php 
require_once 'inc/bootstrap.php';


// je veux réccupérer le premier utilisateur


if(!empty($_POST)){
	
	$errors = array();

$db = App::getDatabase();
$validator = new Validator($_POST);
$validator->isAlpha('username', "Votre pseudo n'est pas valide (alphanumérique sans espace)");
	if($validator->isValid()){
		$validator->isUnique('username', $db, 'users', 'Ce pseudo est déjà pris');
	}
$validator->isEmail('email', "Votre email n'est pas valide");
	if($validator->isValid()){
		$validator->isUnique('email', $db, 'users', 'Cet email est déjà pris');
	}	
$validator->isConfirmed('password', "Votre mot de passe n'est pas valide");

	
	
	
	if($validator->isValid()){
		
		
		App::getAuth()->register($db, $_POST['username'], $_POST['password'], $_POST['email']);
		
		Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte');
		App::redirect('login.php');

	}else{
		$errors = $validator->getErrors();
	}
	

}
?>
<?php require 'inc/header.php'; ?>

<h1>S'inscrire</h1>

<?php if(!empty($errors)): ?>
<div class ="alert alert-danger">
<p> Vous n'avez pas rempli le formulaire correctement</p>
<ul>
<?php foreach($errors as $error): ?>
<li><?= $error; ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>


<form action="" method="POST">


<div class="form-group">

<label for="">Pseudo</label>
<input class="form-control" type="text" name="username"/>
</div>

<div class="form-group">
<label for="">Email</label>
<input class="form-control" type="text" name="email"/>
</div>

<div class="form-group">
<label for="">Mot de passe</label>
<input class="form-control" type="password" name="password"/>
</div>

<div class="form-group">
<label for="">Retapez votre mot de passe</label>
<input class="form-control" type="password" name="password_confirm"/>
</div>

<button class="btn btn-primary" type="submit">M'inscrire</button>

</form>

<?php require 'inc/footer.php'; ?>