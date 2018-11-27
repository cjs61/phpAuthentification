<?php 
require 'inc/bootstrap.php';
// logged_only();
// $auth = new Auth(['restriction_msg' => 'lol tu es bloqué !']); mais il faut pensé à passer tous les paramètres
// le design Pater App s'appel un factory, c'est ici une classe qui ne se charge que d'initialiser les autres classes de façon plus simple (avec moins de paramètres, permet d'initialiser les classes avec leur différentes propriétés, il s'agit bien sûre des propriétés du constructeur ) c'est notamment utile quand j'initialise une classe plusieurs fois

// $auth = App::getAuth();
// $auth->restrict(Session::getInstance());
// $auth->restrict();

App::getAuth()->restrict();
if(!empty($_POST)){

  if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
    $_SESSION['flash']['danger'] = "les mots de passes ne correspondent pas";
  }else{
    $user_id = $_SESSION['auth']->id;
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
      require_once 'inc/db.php';
    $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$password, $user_id]);
    $_SESSION['flash']['success'] = "Votre mot de passe à bien été mis à jour";
  }
}
require 'inc/header.php';
?>




<h1>Bonjour <?= $_SESSION['auth']->username; ?></h1>

<form action="" method="post">
<div class="form-group">
    <input class="form-control" type="password" name="password" placeholder="Changer de mot de passe"/>
</div>
<div class="form-group">
    <input class="form-control" type="password" name="password_confirm" placeholder="Confirmation du mot de passe"/>
</div>

<button class="btn btn-primary">Changer de mot de passe</button>
</form>

<?php require 'inc/footer.php'; ?>

