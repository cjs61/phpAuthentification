<?php

class Auth{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($username, $password, $email){
        $password = password_hash($password, PASSWORD_BCRYPT);
        $token = str::random(60);
	    $this->db->query("INSERT INTO users SET username = ?, password = ?, email = ?, 
            confirmation_token = ?", [
            $username, 
            $password, 
            $email, 
            $token
        ]);

        $user_id = $this->db->LastInsertId();
        mail($email, 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttp://localhost/site5_ok/confirm.php?id=$user_id&token=$token");

    }
    
    public function confirm($user_id, $token, $session){
        $user = $this->db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();


        if($user && $user->confirmation_token == $token){
	
            $this->db->query('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
            $session->write('auth', $user);
            return true;
        } else {
	        return false;
        }   
    }
}
