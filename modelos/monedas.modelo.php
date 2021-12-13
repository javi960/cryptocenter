<?php


require_once "conexion.php";

class ModeloMoneda{

    /**
     * 
     * MOSTAR MONEDAS EN LA BASE DE DATOS
     */

    static public function MdlMostrarMonedaAdmin($tabla,$item,$valor){

        if($item != null){
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM $tabla WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();
        }else{
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM $tabla");

            $stmt -> execute();
            return $stmt -> fetchAll();
        }

    }

    static public function MdlMostrarMoneda($tabla,$item,$valor){

        if($item != null){
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM $tabla WHERE $item = :$item and activo ='1'");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();
        }else{
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM $tabla where activo ='1'");

            $stmt -> execute();
            return $stmt -> fetchAll();
        }

    }

    /**
     * 
     * MOSTAR MONEDAS EN LA BASE DE DATOS SEGUIMIENTO
     */

    static public function MdlMostrarMonedaSUsuario($tabla,$item,$valor){

        if($item != null){
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM $tabla WHERE $item = :$item");

            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetchAll();
        }

    }

    static public function MdlMostrarMonedaSUsuario2($valor){

        
        $stmt = Conexion::conectar() -> prepare("SELECT * FROM monedas as m INNER JOIN monedas_seguimiento 
        as ms WHERE m.id_moneda=ms.id_moneda and m.activo=1 and ms.n_usuario= '$valor'");

        $stmt -> execute();

        return $stmt -> fetchAll();

    }

    static public function MdlMostrarMonedabilletera($valor){

        
        $stmt = Conexion::conectar() -> prepare("SELECT * FROM monedas as m INNER JOIN monedas_seguimiento 
        as ms WHERE m.id_moneda=ms.id_moneda and ms.n_usuario= '$valor'");

        $stmt -> execute();

        return $stmt -> fetchAll();

    }

    /**
     * 
     * INGRESAR MONEDAS EN LA BASE DE DATOS
     */

    static public function MdlIngresarMoneda($tabla,$item){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_moneda, nombre, BenfCompra, BenfVenta, activo) 
        VALUES ('".$item["id_moneda"]."', '".$item["nombre"]."', ".$item["BenfCompra"].", ".$item["BenfVenta"].", '1')");

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * ACTIVAR O DESACTIVAR MONEDAS
     */

    static public function MdlActivarMonedas($tabla,$datos,$befcompra,$befventa,$estado){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET BenfCompra=".$befcompra.", BenfVenta=".$befventa.", activo = :activo
                WHERE id_moneda = :id_moneda");

        $stmt->bindParam(":id_moneda",$datos,PDO::PARAM_STR);
        $stmt->bindParam(":activo",$estado,PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * INGRESAR MONEDAS EN LA BASE DE DATOS SEGUIR
     */

    static public function MdlIngresarSMoneda($tabla,$item){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(id_moneda, n_usuario) 
        VALUES (:id_moneda, :n_usuario)");

        $stmt->bindParam(":id_moneda",$item["id_moneda"],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$item["n_usuario"],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * ELIMINAR MONEDAS EN LA BASE DE DATOS SEGUIR
     */

    static public function MdlEliminarSMoneda($tabla,$datos){

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_moneda = :id_moneda AND n_usuario=:n_usuario");

        $stmt->bindParam(":id_moneda",$datos["id_moneda"],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos["n_usuario"],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * MOSTAR MONEDAS DE CARTERA EN LA BASE DE DATOS
     */

    static public function MdlMostrarCarteraMoneda($item,$valor){

        if($item != null){
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM monedas_cartera WHERE n_usuario = :n_usuario AND id_moneda=:id_moneda");

            $stmt -> bindParam(":n_usuario", $valor, PDO::PARAM_STR);
            $stmt -> bindParam(":id_moneda", $item, PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();
        }else{
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM monedas_cartera WHERE n_usuario = :n_usuario");
            $stmt -> bindParam(":n_usuario", $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetchAll();
        }

    }

    /**
     * 
     * MOSTAR BENEFICIOS
     */

    static public function MdlMostrarBeneficios($item,$fechas){

        if($item != null){
            $stmt = Conexion::conectar() -> prepare("SELECT bp.id_beneficioP, bp.cantidad AS Cbeneficio,
            bp.id_compraVenta, cv.id, cv.moneda, cv.valor, cv.fecha_c, cv.t_accion, cv.cantidad,
            cv.dinero, cv.n_usuario FROM `beneficios_plataforma` as bp 
            INNER JOIN compra_venta as cv WHERE bp.id_compraVenta=cv.id AND cv.moneda==:id_moneda AND
            cv.fecha_c LIKE :anyo ORDER BY cv.fecha_c ASC");

            $stmt -> bindParam(":id_moneda", $item, PDO::PARAM_STR);
            $stmt->bindParam(":anyo",$fechas,PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();
        }else{
            $stmt = Conexion::conectar() -> prepare("SELECT bp.id_beneficioP, bp.cantidad AS Cbeneficio,
            bp.id_compraVenta, cv.id, cv.moneda, cv.valor, cv.fecha_c, cv.t_accion, cv.cantidad,
            cv.dinero, cv.n_usuario FROM `beneficios_plataforma` as bp 
            INNER JOIN compra_venta as cv WHERE bp.id_compraVenta=cv.id AND
            cv.fecha_c LIKE :anyo ORDER BY cv.fecha_c ASC");            
            $stmt->bindParam(":anyo",$fechas,PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetchAll();
        }

    }

}