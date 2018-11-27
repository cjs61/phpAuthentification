<?php

class Auth{

    private $session;

    private $options = [
        'restriction_msg' => "Vous n'avez pas le droit d'accèder à cette page"
    ];


    public function __construct($session, $options = [])
    {
        $this->options = array_merge($this->options, $options);
        $this->session = $session;
    }

    public function register($db, $username, $password, $email){
        $password = password_hash($password, PASSWORD_BCRYPT);
        $token = str::random(60);
	    $db->query("INSERT INTO users SET username = ?, password = ?, email = ?, 
            confirmation_token = ?", [
            $username, 
            $password, 
            $email, 
            $token
        ]);

        $user_id = $this->db->LastInsertId();
        mail($email, 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/site5_ok/confirm.php?id=$user_id&token=$token");

    }
    
    public function confirm($db, $user_id, $token){
        $user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();


        if($user && $user->confirmation_token == $token){
	
            $db->query('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
            $this->session->write('auth', $user);
            return true;
        } else {
	        return false;
        }   
    }

    public function restrict(){
        if (!$this->session->read('auth')) {
            $this->session->setFlash('danger', $this->options['restriction_msg']);
            header('Location: login.php');
            exit();
        }
    }

    public function user(){
        if(!$this->session->read('auth')){
            return false;
        }
        return $this->session->read('auth');
    }

    public function connect($user){
        $this->session->write('auth', $user);
    }

    public function connectFromCookie($db){

        // if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
        if (isset($_COOKIE['remember']) && !$this->user()) {
            // require_once 'db.php';
            // if (!isset($pdo)) {
            //     global $pdo;
            // }
    
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);
            $user_id = $parts[0];
            $user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();
            if ($user) {
                $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'tetedeslip');
                if ($expected == $remember_token) {
                    // session_start();
                    $this->connect($user);
                    // $_SESSION['auth'] = $user;
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 31);
                } else {
                    setcookie('remember', null, -1);
                }
                    } else {
                setcookie('remember', null, -1);
                    }
        }
    }

    public function login($db, $username, $password, $remember = false) {
        $user = $db -> query('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();
        if (password_verify($_POST['password'], $user -> password)) {
            // $_SESSION['auth'] = $user;
            $this->connect($user);
            // ce messages flash n'est plus ici
            // $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté';
            if($remember){
                $this->remember($db, $user->id);
            }
            header('Location: account.php');
            exit();
        } else {
        $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect';
        }
    }

    public function remember($db, $user_id){
        // if ($_POST['remember']) {
        if($remember){ 
            // $remember_token = str_random(250); car j'ai créé une fonction statique
            $remember_token = Str::random();
            $db -> query('UPDATE users SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);
            setcookie('remember', $user_id.'=='.$remember_token.sha1($user_id .'tetedeslip'), time() + 60 * 60 * 24 * 15);
        }
    }
}
