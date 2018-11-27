<?php
// cette classe contient tout ce qui est générique dans mon application
// Le but est de créer une fonction static pour l'accès à la bdd...
class App{

  static $db = null;

  static function getDatabase(){
    if(!self::$db){
      self::$db = new Database('root', '', 'photo');
    }
    return self::$db;
  }

  static function getAuth(){
    return new Auth(Session::getInstance(), ['restriction_msg' => 'lol tu es bloqué !']);
  }

  static function redirect($page){
    header ("location: $page");
    exit();
  }
}