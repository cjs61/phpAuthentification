<?php 
require_once 'inc/functions.php';

session_start();

// je veux réccupérer le premier utilisateur
require 'class/Database.php';
$db = newDatabase('root', 'root','photo');
$user = $db->query('SELECT * FROM users WHERE id = ?', [0])->fetch();
debug($user);
die();

if(!empty($_POST)){
	
	$errors = array();
require_once 'inc/db.php';
	
	if(empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])){
		$errors['username'] = "Votre pseudo n'est pas valide (alphanumérique sans espace)";
}else {
	$req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
	$req->execute([$_POST['username']]);
	$user = $req->fetch();
	if ($user){
		$errors['username'] = 'Ce pseudo est déjà pris';
	}
	
}
		
	if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	$errors['email'] = "Votre email n'est pas valide";
} else {
	$req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
	$req->execute([$_POST['email']]);
	$user = $req->fetch();
	if ($user){
		$errors['email'] = 'Cet email est déjà pris';
	}
}
	
	if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
	$errors['password'] = "Votre mot de passe n'est pas valide";
}
	
	if(empty($errors)){
	
$req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
$password = password_hash($_POST ['password'], PASSWORD_BCRYPT);
$token = str_random(60);
$req->execute([$_POST['username'], $password, $_POST['email'], $token]);
$user_id = $pdo->LastInsertId();
mail($_POST['email'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/site5_ok/confirm.php?id=$user_id&token=$token");
$_SESSION['flash']['success'] = 'Un email de confirmation vous a été envoyé pour valider votre compte';
header('location: login.php');
exit();
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