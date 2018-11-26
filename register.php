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


// echo '<pre>';
// var_dump($validator);
// var_dump($validator->isValid());
// die();
	
	
	
	if($validator->isValid()){

		$password = password_hash($_POST ['password'], PASSWORD_BCRYPT);
		$token = str_random(60);
	
$db->query("INSERT INTO users SET username = ?, password = ?, email = ?, 
confirmation_token = ?", 
[$_POST['username'], 
$password, 
$_POST['email'], 
$token
]);

$user_id = $db->LastInsertId();
mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/site5_ok/confirm.php?id=$user_id&token=$token");
$_SESSION['flash']['success'] = 'Un email de confirmation vous a été envoyé pour valider votre compte';
header('location: login.php');
exit();
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