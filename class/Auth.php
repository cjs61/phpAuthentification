<?php

class Auth{

    private $options = [
        'restriction_msg' => "Vous n'avez pas le droit d'accèder à cette page"
    ];


    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
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
    
    public function confirm($db, $user_id, $token, $session){
        $user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();


        if($user && $user->confirmation_token == $token){
	
            $db->query('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
            $session->write('auth', $user);
            return true;
        } else {
	        return false;
        }   
    }

    public function restrict($session){
        if (!$session->read('auth')) {
            $session->setFlash('danger', $this->options['restriction_msg']);
            header('Location: login.php');
            exit();
        }
    }
}
