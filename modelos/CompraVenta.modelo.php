<?php


require_once "conexion.php";

class ModeloCompraVenta{
    /**
     * 
     * COMPROBAR MONEDA EN LA CARTERA
     * Funcion para extraer los datos de una moneda de la tabla monedas_cartera
     */

    static public function MdlSelectMonedasCartera($datos){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM monedas_cartera
        WHERE n_usuario=:n_usuario AND id_moneda=:id_moneda");
        
        $stmt->bindParam(":id_moneda",$datos['id_moneda'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch();

    }

    static public function MdlSelectTodasMonedasCartera($datos){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM monedas_cartera
        WHERE n_usuario=:n_usuario");
        
        $stmt->bindParam(":n_usuario",$datos,PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll();

    }
    /**
     * 
     * ACTUALIZAR CARTERA
     */

    static public function MdlUpdateMonedasCartera($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE monedas_cartera SET cantidad=:cantidad
        WHERE n_usuario=:n_usuario AND id_moneda=:id_moneda");
        
        $stmt->bindParam(":cantidad",$datos['cantidad'],PDO::PARAM_STR);
        $stmt->bindParam(":id_moneda",$datos['id_moneda'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * INSERTAR CARTERA
     */

    static public function MdlInsertarMonedasCartera($datos){

        $stmt = Conexion::conectar()->prepare('INSERT INTO monedas_cartera(cantidad, id_moneda, n_usuario)
        VALUES ('.$datos["cantidad"].',"'.$datos["id_moneda"].'","'.$datos["n_usuario"].'")');

        //$stmt->bindParam(":cantidad",$datos['cantidad'],PDO::PARAM_INT);
        //$stmt->bindParam(":id_moneda",$datos['id_moneda'],PDO::PARAM_STR);
        //$stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);
        
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * INSERTAR REGISTRO EN LA TABLA REGISTRO COMPRA/VENTA
     */

    static public function MdlInsertarRegistroCompraVenta($datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO compra_venta(moneda, valor, fecha_c, t_accion, cantidad,dinero, n_usuario)
        VALUES (:moneda,:valor,:fecha_c, :t_accion, :cantidad,:dinero,:n_usuario)");
        
        $stmt->bindParam(":moneda",$datos['moneda'],PDO::PARAM_STR);
        $stmt->bindParam(":valor",$datos['valor'],PDO::PARAM_STR);
        $stmt->bindParam(":fecha_c",$datos['fecha_c'],PDO::PARAM_STR);
        $stmt->bindParam(":t_accion",$datos['t_accion'],PDO::PARAM_STR);
        $stmt->bindParam(":cantidad",$datos['cantidad'],PDO::PARAM_STR);
        $stmt->bindParam(":dinero",$datos['dinero'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * INSERTAR CARTERA
     */

    static public function MdlInsertarBeneficios($datos){

        $stmt = Conexion::conectar()->prepare('INSERT INTO beneficios_plataforma(cantidad, id_compraVenta)
        VALUES ('.$datos["cantidad"].','.$datos["id_compraVenta"].')');

        //$stmt->bindParam(":cantidad",$datos['cantidad'],PDO::PARAM_INT);
        //$stmt->bindParam(":id_moneda",$datos['id_moneda'],PDO::PARAM_STR);
        //$stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);
        
        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * MOSTRAR FILA DE LA TABLA COMPRA VENTA
     */

    static public function MdlSelectIdCompraVenta($datos){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM compra_venta
        WHERE n_usuario=:n_usuario AND fecha_c=:fecha_c AND moneda=:moneda");
        
        $stmt->bindParam(":fecha_c",$datos['fecha_c'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);
        $stmt->bindParam(":moneda",$datos['moneda'],PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch();

    }

    static public function MdlSelectCompra($datos){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM compra_venta
        WHERE n_usuario=:n_usuario AND moneda=:moneda AND t_accion='comprar' AND
        fecha_c LIKE :anyo ORDER BY fecha_c ASC");
        
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);
        $stmt->bindParam(":moneda",$datos['moneda'],PDO::PARAM_STR);
        $stmt->bindParam(":anyo",$datos['anyo'],PDO::PARAM_STR);
        

        $stmt -> execute();

        return $stmt -> fetchAll();

    }

    static public function MdlSelectVenta($datos){

        $stmt = Conexion::conectar()->prepare("SELECT * FROM compra_venta
        WHERE n_usuario=:n_usuario AND moneda=:moneda AND t_accion='vender' AND
        fecha_c LIKE :anyo ORDER BY fecha_c ASC");
        
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);
        $stmt->bindParam(":moneda",$datos['moneda'],PDO::PARAM_STR);
        $stmt->bindParam(":anyo",$datos['anyo'],PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetchAll();

    }
}