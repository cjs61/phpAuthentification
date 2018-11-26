<?php

class Session{

    // création d'un singleton pour n'avoir qu'une session
    static $instance;

    static function getInstance(){
        if(!self::$instance){
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function __construct()
    {
        session_start();
    }

    public function setFlash($key, $message){
        $_SESSION['flash'][$key] = $message;

    }

    public function hasFlashes(){
        return isset($_SESSION['flash']);
    }

    public function getFlashes(){
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    public function write($key, $value){
        $_SESSION[$key] = $value;
    }

    public function read($key){
        // les : évitent de mettre un if
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
}