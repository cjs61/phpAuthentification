<?php 
require 'inc/bootstrap.php';
// require_once 'inc/functions.php';
// reconnect_from_cookie();

$auth = App::getAuth();
// j'ai besoin de l'accès à la bdd dans la fonction connectFromCookie
$db = App::getDatabase();
$auth->connectFromCookie($db);
// if(isset($_SESSION['auth'])){
// 	header('location: account.php');
// 	exit();
// }

// ($auth->user()) signifie que l'utilisateur est connecté

if($auth->user()){
	App::redirect('account.php');
}
if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
	$user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
	if($user){
		$_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
		header('Location: account.php');
        exit();
	}

}
?>

<?php require 'inc/header.php'; ?>


<h1>Se connecter</h1>

<form action="" method="POST">


<div class="form-group">
<label for="">Pseudo ou email</label>
<input type="text" name="username" class="form-control"/>
</div>


<div class="form-group">
<label for="">Mot de passe <a href="forget.php">(J'ai oublié mon mot de passe)</a></label>
<input type="password" name="password" class="form-control"/>
</div>

<div class="form-group">
	<label>
		<input type="checkbox" name="remember" value="1"/> Se souvenir de moi
	</label>
</div>

<button type="submit" class="btn btn-primary">Se connecter</button>

</form>


<?php require 'inc/footer.php'; ?>

