<?php
// cette classe contient tout ce qui est générique dans mon application
// Le but est de créer une fonction static pour l'accès à la bdd
class App{


  static function getDatabase(){
    return new Database('root', '', 'photo');
  }
}