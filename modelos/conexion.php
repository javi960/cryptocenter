<?php

class Conexion{

    static public function conectar(){

        $link = new PDO("mysql:host=localhost;dbname=puuzxume_cryptocenter",
                        "puuzxume_cryptocenter",
                        "Cripto1234$");

        $link -> exec("set names utf8");

        return $link;
        $link=null;
    }
}