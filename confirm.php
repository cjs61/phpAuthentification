<?php

$user_id = $_GET['id'];

$token = $_GET['token'];

$db = App::getDatabase();

$user = $db->query('SELECT * FROM users WHERE id = ?', )->fetch([$user_id]);

session_start();

if($user && $user->confirmation_token == $token){
	
		$pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?')->execute([$user_id]);
		$_SESSION['flash']['success'] = 'Votre compte a bien été validé';
	$_SESSION['auth'] = $user;
	header('Location: account.php');
} else {
	$_SESSION['flash']['danger'] = "Ce token n'est plus valide";
	header('Location: login.php');
}