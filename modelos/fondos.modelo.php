<?php


require_once "conexion.php";

class ModeloFondos{
    /**
     * 
     * ACTUALIZAR FONDOS
     */

    static public function MdlUpdateFondos($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE fondos SET n_fondos=:n_fondos
        WHERE n_usuario=:n_usuario");
        
        $stmt->bindParam(":n_fondos",$datos['n_fondos'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * INSERTAR REGISTRO EN LA TABLA REGISTRO DE FONDOS
     */

    static public function MdlInsertarRegistroFondos($datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO registro_fondo(accion, cantidad_fondo, fecha, id_usuario)
        VALUES (:accion,:cantidad_fondo,:fecha,:id_usuario)");
        
        $stmt->bindParam(":accion",$datos['accion'],PDO::PARAM_STR);
        $stmt->bindParam(":cantidad_fondo",$datos['cantidad_fondo'],PDO::PARAM_STR);
        $stmt->bindParam(":fecha",$datos['fecha'],PDO::PARAM_STR);
        $stmt->bindParam(":id_usuario",$datos['id_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }
}