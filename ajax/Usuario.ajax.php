<?php

require_once "../controladores/monedas.controlador.php";
require_once "../modelos/monedas.modelo.php";
require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";




/*
    VER DATOS DEL USUARIO
*/

if (isset($_POST["Nusuario"])) {
    $respuesta2 =ModeloUsuarios::MdlMostrarDatosCompletoUsuario($_POST["Nusuario"]);
    echo json_encode($respuesta2);
}
/*
    COMPROBAR VALIDACION
*/
if (isset($_POST["Eusuario"])) {
    $url="UPDATE validaciones SET validado=3 WHERE n_usuario='".$_POST["Eusuario"]."'";
    $respuesta1 =ModeloUsuarios::MdlSentenciatUniversal($url);
    $url="DELETE FROM datos_usuario WHERE n_usuario = '".$_POST["Eusuario"]."'";
    $respuesta2 =ModeloUsuarios::MdlSentenciatUniversal($url);
    $url="DELETE FROM fondos WHERE n_usuario = '".$_POST["Eusuario"]."'";
    $respuesta3 =ModeloUsuarios::MdlSentenciatUniversal($url);
    if ($respuesta1=='ok' && $respuesta1=='ok' && $respuesta3=='ok') {
        echo json_encode(true);
    }
    
    
}
/*
    DNI USUARIO
*/
if (isset($_POST["dni"])) {
    $respuesta2 =ModeloUsuarios::MdlMostrarUsuarios("datos_usuario","dni",$_POST["dni"]);
    if ($respuesta2!=null) {
        $respuesta =ModeloUsuarios::MdlMostrarUsuarios("usuarios","n_usuario",$respuesta2["n_usuario"]);
        if (isset($respuesta)) {
            echo json_encode(false);
        }else{
            echo json_encode(true);   
        }
    }else {
        echo json_encode(false);
    }
    
    
    
}
/*
    VER DATOS DEL USUARIO
*/
if (isset($_POST["usuarioR"])) {
    $respuesta2 =ModeloUsuarios::MdlMostrarUsuarios("usuarios","n_usuario",$_POST["usuarioR"]);
    if ($respuesta2!=null) {
        echo json_encode(true);
    }else {
        echo json_encode(false);
    }
    
}
/*
    VER EMAIL DEL USUARIO
*/
if (isset($_POST["emailR"])) {
    $respuesta2 =ModeloUsuarios::MdlMostrarUsuarios("usuarios","email",$_POST["emailR"]);
    if ($respuesta2!=null) {
        echo json_encode(true);
    }else {
        echo json_encode(false);
    }
    
}


