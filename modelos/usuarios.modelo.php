<?php


require_once "conexion.php";

class ModeloUsuarios{

    /**
     * 
     * MOSTAR USUARIOS
     */

    static public function MdlMostrarUsuarios($tabla,$item,$valor){

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
    /*funcion para mostrar todos los datos de un usuario de las tablas usuarios, fondos y datos_usuario
    */
    static public function MdlMostrarDatosCompletoUsuario($valor){

        
        $stmt = Conexion::conectar() -> prepare("SELECT * FROM usuarios as u INNER JOIN fondos as f 
                                                INNER JOIN datos_usuario as du where 
                                                u.n_usuario=du.n_usuario and u.n_usuario=f.n_usuario and u.n_usuario = '$valor'");

        //$stmt -> bindParam(":u.n_usuario", $valor, PDO::PARAM_STR);

        $stmt -> execute();

        return $stmt -> fetch();

    }

    /**
     * 
     * INGRESAR USUARIO
     */

    static public function MdlIngresarUsuario($tabla,$item){

        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(n_usuario, pass, email, rol, Activo) 
        VALUES (:n_usuario, :pass, :email, :rol, :Activo)");

        $stmt->bindParam(":n_usuario",$item["n_usuario"],PDO::PARAM_STR);
        $stmt->bindParam(":pass",$item["pass"],PDO::PARAM_STR);
        $stmt->bindParam(":email",$item["email"],PDO::PARAM_STR);
        $stmt->bindParam(":rol",$item["rol"],PDO::PARAM_STR);
        $stmt->bindParam(":Activo",$item["Activo"],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }


    /**
     * 
     * MOSTAR FONDO USUARIO
     */

    static public function MdlMostrarFUsuario($valor){

        if($valor != null){
            $stmt = Conexion::conectar() -> prepare("SELECT * FROM usuarios as u INNER JOIN fondos as f WHERE u.n_usuario=f.n_usuario and u.n_usuario='$valor'");

            $stmt -> execute();

            return $stmt -> fetch();
        }

    }
    /**
     * 
     * ACTIVAR O DESACTIVAR USUARIOS
     */

    static public function MdlActivarUsuarios($tabla,$datos,$estado){

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET Activo = :Activo
                WHERE n_usuario = :n_usuario");

        $stmt->bindParam(":n_usuario",$datos,PDO::PARAM_STR);
        $stmt->bindParam(":Activo",$estado,PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * COMPROBAR DATOS EN LA TABLA DATOS USUARIOS Y FONDOS
     */

    static public function MdlComprobarDatosUsuarios($item,$valor){

        $stmt = Conexion::conectar() -> prepare("SELECT * FROM fondos WHERE $item = :$item");

        $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt -> execute();

        $respuesta=$stmt -> fetch();

        $stmt2 = Conexion::conectar() -> prepare("SELECT * FROM datos_usuario WHERE $item = :$item");

        $stmt2 -> bindParam(":".$item, $valor, PDO::PARAM_STR);

        $stmt2 -> execute();

        $respuesta2=$stmt2 -> fetch();

        if($respuesta!=null && $respuesta2!=null){
            return "ok";
        }else{
            return "no";
        }

    }

    /**
     * 
     * ACTUALIZAR LOS DATOS DE LA TABLA DATOS USUARIOS
     */

    static public function MdlUpdateDatosUsuarios($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE datos_usuario SET nombre_u=:nombre_u,apellido1=:apellido1,apellido2=:apellido2,
        telefono=:telefono,direccion=:direccion,poblacion=:poblacion,provincia=:provincia,pais=:pais WHERE n_usuario=:n_usuario");
        
        
        $stmt->bindParam(":nombre_u",$datos['nombre_u'],PDO::PARAM_STR);
        $stmt->bindParam(":apellido1",$datos['apellido1'],PDO::PARAM_STR);
        $stmt->bindParam(":apellido2",$datos['apellido2'],PDO::PARAM_STR);
        $stmt->bindParam(":telefono",$datos['telefono'],PDO::PARAM_STR);
        $stmt->bindParam(":direccion",$datos['direccion'],PDO::PARAM_STR);
        $stmt->bindParam(":poblacion",$datos['poblacion'],PDO::PARAM_STR);
        $stmt->bindParam(":provincia",$datos['provincia'],PDO::PARAM_STR);
        $stmt->bindParam(":pais",$datos['pais'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    static public function MdlUpdateDatosCuentaUsuario($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE usuarios SET pass=:pass,email=:email WHERE n_usuario=:n_usuario");
        
        $stmt->bindParam(":pass",$datos['pass'],PDO::PARAM_STR);
        $stmt->bindParam(":email",$datos['email'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    /**
     * 
     * ACTUALIZAR LOS DATOS DE LA TABLA FONDOS
     */

    static public function MdlUpdateDatosFondos($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE fondos SET n_fondos=:n_fondos,moneda_fondo=:moneda_fondo
        WHERE n_usuario=:n_usuario");
        
        $stmt->bindParam(":n_fondos",$datos['n_fondos'],PDO::PARAM_STR);
        $stmt->bindParam(":moneda_fondo",$datos['moneda_fondo'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    static public function MdlUpdatePassUsuario($datos){

        $stmt = Conexion::conectar()->prepare("UPDATE usuarios SET pass=:pass WHERE n_usuario=:n_usuario");
        
        $stmt->bindParam(":pass",$datos['pass'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    static public function MdlIngresarDatosUsuario($datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO datos_usuario(dni, nombre_u, apellido1, apellido2, 
        telefono, direccion, poblacion, provincia, pais, n_usuario) 
        VALUES (:dni, :nombre_u, :apellido1, :apellido2, :telefono, :direccion, :poblacion, :provincia, :pais, :n_usuario)");

        $stmt->bindParam(":dni",$datos['dni'],PDO::PARAM_STR);
        $stmt->bindParam(":nombre_u",$datos['nombre_u'],PDO::PARAM_STR);
        $stmt->bindParam(":apellido1",$datos['apellido1'],PDO::PARAM_STR);
        $stmt->bindParam(":apellido2",$datos['apellido2'],PDO::PARAM_STR);
        $stmt->bindParam(":telefono",$datos['telefono'],PDO::PARAM_STR);
        $stmt->bindParam(":direccion",$datos['direccion'],PDO::PARAM_STR);
        $stmt->bindParam(":poblacion",$datos['poblacion'],PDO::PARAM_STR);
        $stmt->bindParam(":provincia",$datos['provincia'],PDO::PARAM_STR);
        $stmt->bindParam(":pais",$datos['pais'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }
    static public function MdlInsertarTuplaFondos($datos){

        $stmt = Conexion::conectar()->prepare("INSERT INTO fondos(n_fondos, moneda_fondo, n_usuario)
        VALUES (:n_fondos,:moneda_fondo,:n_usuario)");
        
        $stmt->bindParam(":n_fondos",$datos['n_fondos'],PDO::PARAM_STR);
        $stmt->bindParam(":moneda_fondo",$datos['moneda_fondo'],PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$datos['n_usuario'],PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    static public function MdlInsertarValidacion($valido,$usuario){

        $stmt = Conexion::conectar()->prepare("INSERT INTO validaciones(validado, n_usuario)
        VALUES (:validado,:n_usuario)");
        
        $stmt->bindParam(":validado",$valido,PDO::PARAM_STR);
        $stmt->bindParam(":n_usuario",$usuario,PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    static public function MdlMostrarValidacion($usuario){

        if ($usuario!=null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM validaciones WHERE n_usuario=:n_usuario");
        
            $stmt->bindParam(":n_usuario",$usuario,PDO::PARAM_STR);

            $stmt -> execute();

            return $stmt -> fetch();
        }else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM validaciones WHERE validado=0");

            $stmt -> execute();

            return $stmt -> fetchAll();
        }
        

    }

    static public function MdlSentenciatUniversal($url){

        $stmt = Conexion::conectar()->prepare($url);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }
        

    }
    static public function MdlBorrarUsuario($tabla,$datos){

        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE n_usuario = :n_usuario");

        $stmt->bindParam(":n_usuario",$datos,PDO::PARAM_STR);

        if($stmt->execute()){
            return "ok";
        }else{
            return "error";
        }

    }

    

}