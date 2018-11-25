<?php
// cette classe contient tout ce qui est générique dans mon application
// Le but est de créer une fonction static pour l'accès à la bdd
class App{

static $db = null;

  static function getDatabase(){
    if(!self::$db){
      self::$db = new Database('root', '', 'photo');
    }
    return self::$db;
  }
}