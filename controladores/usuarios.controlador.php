<?php

class ControladorUsuarios
{
    /*
        COMPROBAR LOGIN DEL USUARIO
    */
    static public function ctrIngresoUsuarios()
    {
        //comprobamos si pasan un usuario
        if (isset($_POST["user"])) {
            //comprueba si cumple con el estandar de nombre de usuario
            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["user"])) {
                $tabla = "usuarios";
                $item = "n_usuario";
                $valor = $_POST["user"];
                //buscamos el usuario
                $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);
                //comprobamos el usuario y la contraseña con las de la base de datos y si está activo
                if ($respuesta["n_usuario"] == $_POST["user"] && password_verify($_POST["password"],$respuesta["pass"]) &&
                $respuesta["Activo"] == "1") {
                    //creamos las sesiones necesarios para interactuar con la aplicacion
                    $_SESSION["iniciarSesion"] = "ok";
                    $_SESSION["usuario"] = $respuesta["n_usuario"];
                    $_SESSION["rol"] = $respuesta["rol"];
                    //comprobamos el tipo de usuario para diferenciar las paginas a las que puede acceder
                    if ($respuesta["rol"]=="Administrador") {
                        $_SESSION["icono"] = "profile.png";
                        echo '<script>
                    
                                window.location="monedas";
                            
                            </script>';
                    }else {
                        $_SESSION["icono"] = random_int(1, 5).".png";
                        echo '<script>
                    
                                window.location="mpUsuario";
                            
                            </script>';
                    }
                    
                } else {
                    echo '</br><div class="alert alert-danger">Error al ingresar</div>';
                }
            }
        }
    }
    //MOSTRAR USUARIOS DE LA BASE DE DATOS
    static public function ctrMostrarUsuarios($item,$valor){
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);

        return $respuesta;
    }
    //MOSTRAR VALIDACIONES DE LOS USUARIOS
    static public function ctrMostrarTvalidaUsuarios($item,$valor){
        $tabla = "validaciones";
        $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);

        return $respuesta;
    }
    //MOSTRAR TODOS LOS DATOS DE USUARIOS
    static public function ctrMostrarDatosCompletoUsuario($valor){
        $respuesta = ModeloUsuarios::MdlMostrarDatosCompletoUsuario($valor);

        return $respuesta;
    }
    //MOSTRAR VALIDACIONES DE TODOS LOS USUARIOS
    static public function ctrMostrarValidacion($valor){
        $respuesta = ModeloUsuarios::MdlMostrarValidacion($valor);

        return $respuesta;
    }

        /*
            CREAR USUARIO

        */
    static public function ctrCrearUsuario()
    {
        //comprobamos que se han aceptado los terminos
        if (isset($_POST["terms"])) {
            //comprobamos todos los campos
            if (
                isset($_POST["usuario"]) && isset($_POST["email"])
                && isset($_POST["contraseña"]) && isset($_POST["rcontraseña"])
            ) {

                $tabla = "usuarios";
                $item = "n_usuario";
                $valor = $_POST["usuario"];
                //comprobamos que el usuario no está en uso
                $comprobarUsuario = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);
                //comprobamos que el email no está en uso
                $comprobarEmail = ModeloUsuarios::MdlMostrarUsuarios("usuarios","email",$_POST["email"]);
                if ($comprobarUsuario==null) {
                    if ($comprobarEmail==null) {
                        //comprobamos la estructura de los datos
                        if (
                            preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuario"])
                            && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)
                            && preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/', $_POST["contraseña"])
                        ) {
                            //creamos el array de insercion
                            $datos = array(
                                "n_usuario" => $_POST["usuario"],
                                "pass" => password_hash($_POST["contraseña"],PASSWORD_DEFAULT),
                                "email" => $_POST["email"],
                                "rol" => "Invitado",
                                "Activo" => "1"
                            );
                            //inseertamos el usuario en la tabla
                            $respuesta = ModeloUsuarios::MdlIngresarUsuario($tabla, $datos);
        
                            if ($respuesta == "ok") {
                                echo '<script>
                            
                                    Swal.fire({
                                        icon: "success",
                                        title: "Perfecto",
                                        text: "¡Usuario Registrado Correctamente!",
                                        confirmButtonText: "Cerrar"
                                    }).then((result)=>{
                                        if(result.value){
                                            window.location = "login";
                                        }
                                    })
        
                                    
                                    </script>';
                            }
                        } else {
                            //mostramos el mensaje de error con los fallos
                            $datosMal = "";
                            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuario"])) {
                                $datosMal = $datosMal . "Usuario no valido ";
                            }
                            if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                $datosMal = $datosMal . "Email no valido ";
                            }
                            if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/', $_POST["contraseña"])) {
                                $datosMal = $datosMal . "Contraseña no valida, siga las instrucciones para las contraseñas ";
                            }
                            //mostramos mensaje de ejecutado y redireccionamos
                            echo '<script>
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "'.$datosMal.'",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "registro";
                                }
                            })
        
                            
                            </script>';
                        }
                    }else {
                        //mostramos el mensaje de error con el fallo
                        echo '<script>
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "¡El Email ya existe en el sistema!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "registro";
                                }
                            })
        
                            
                            </script>';
                    }
                }else {
                    //mostramos el mensaje de error con el fallo
                    echo '<script>
                        
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "¡El nombre de usuario ya existe en el sistema!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "registro";
                            }
                        })
    
                        
                        </script>';
                }
                
            } else {
                //mostramos el mensaje de error con los fallos
                $datosVacios = "";
                if (!isset($_POST["usuario"])) {
                    $datosVacios = $datosVacios . "Debes rellenar el cuadro de nombre de usuario ";
                }
                if (!isset($_POST["email"])) {
                    $datosVacios = $datosVacios . "Debes rellenar el cuadro de email ";
                }
                if (!isset($_POST["contraseña"])) {
                    $datosVacios = $datosVacios . "Debes rellenar el cuadro de contraseña ";
                }
                if (!isset($_POST["rcontraseña"])) {
                    $datosVacios = $datosVacios . "Debes rellenar el cuadro de repite la contraseña";
                }
                echo '<script>
                    
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "' . $datosVacios . '",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "registro";
                        }
                    })

                    
                    </script>';
            }
        }
    }


        /*
            CREAR USUARIO ADMINISTRADOR

        */
    static public function ctrCrearUsuarioAdmin()
    {

        //comprobamos los datos
        if (
            isset($_POST["usuarioAdmin"]) && isset($_POST["emailAdmin"])
            && isset($_POST["contraseñaAdmin"]) && isset($_POST["rcontraseñaAdmin"])
        ) {
            $tabla = "usuarios";
            $item = "n_usuario";
            $valor = $_POST["usuarioAdmin"];
            //comprobamos que el usuario no está en uso
            $comprobarUsuario = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);
            //comprobamos el email
            $comprobarEmail = ModeloUsuarios::MdlMostrarUsuarios("usuarios","email",$_POST["emailAdmin"]);
            if ($_POST["contraseñaAdmin"]==$_POST["rcontraseñaAdmin"]) {
                if ($comprobarUsuario==null) {
                    if ($comprobarEmail==null) {
                        //comprobamos la estructura de los campos
                        if (
                            preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuarioAdmin"])
                            && filter_var($_POST["emailAdmin"], FILTER_VALIDATE_EMAIL)
                            && preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/', $_POST["contraseñaAdmin"])
                        ) {
            
                            //preparamos el array para insercion
                            $datos = array(
                                "n_usuario" => $_POST["usuarioAdmin"],
                                "pass" => $_POST["contraseñaAdmin"],
                                "email" => $_POST["emailAdmin"],
                                "rol" => "Administrador",
                                "Activo" => "1"
                            );
                            //insertamos el usuario
                            $respuesta = ModeloUsuarios::MdlIngresarUsuario($tabla, $datos);
                            //mostramos mensaje de ejecutado y redireccionamos
                            if ($respuesta == "ok") {
                                echo '<script>
                            
                                    Swal.fire({
                                        icon: "success",
                                        title: "Perfecto",
                                        text: "¡Usuario Administrador Registrado Correctamente!",
                                        confirmButtonText: "Cerrar"
                                    }).then((result)=>{
                                        if(result.value){
                                            window.location = "gesUsuarios";
                                        }
                                    })
            
                                    
                                    </script>';
                            }
                        } else {
                            //mostramos el mensaje de error con los fallos
                            $datosMal = "";
                            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["usuarioAdmin"])) {
                                $datosMal = $datosMal . "Usuario no valido ";
                            }
                            if (filter_var($_POST["emailAdmin"], FILTER_VALIDATE_EMAIL)) {
                                $datosMal = $datosMal . "Email no valido ";
                            }
                            if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,16}$/', $_POST["contraseñaAdmin"])) {
                                $datosMal = $datosMal . "Contraseña no valida, siga las instrucciones para las contraseñas ";
                            }
                            echo '<script>
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "'.$datosMal.'",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "gesUsuarios";
                                }
                            })
            
                            
                            </script>';
                        }
                    }else {
                        //mostramos el mensaje de error con el fallo
                        echo '<script>
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "¡El Email ya existe en el sistema!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "gesUsuarios";
                                }
                            })
        
                            
                            </script>';
                    }               
                }else {
                    //mostramos el mensaje de error con el fallo
                    echo '<script>
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "¡El nombre de usuario ya existe en el sistema!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "gesUsuarios";
                                }
                            })
        
                            
                            </script>';
                }
            }else {
                //mostramos el mensaje de error con el fallo
                echo '<script>
                        
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "¡Las Contraseñas no Coinciden!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "gesUsuarios";
                            }
                        })
    
                        
                        </script>';
            }
            
            
        }
    }

    /*
        ACTIVAR O DESACTIVAR USUARIO

    */

    static public function ctrActivarUsuario(){
        //comprobamos que nos pasan un usuario
        if (isset($_GET["Nusuario"])) {
            
            
            $tabla = "usuarios";
            $item = "n_usuario";
            //buscamos el ususario
            $DUsuario = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$_GET["Nusuario"]);
            //comprobamos si está o no activo
            if ($DUsuario["Activo"] == "1") {
                //desactivamos el usuario
                $respuesta = ModeloUsuarios::MdlActivarUsuarios($tabla,$DUsuario["n_usuario"],"0");
                
            }else {
                //activamos el usuario
                $respuesta = ModeloUsuarios::MdlActivarUsuarios($tabla,$DUsuario["n_usuario"],"1");
            }
            //mostramos mensaje de ejecutado y redireccionamos
            if($respuesta == "ok"){
                echo '<script>
            
                    Swal.fire({
                        icon: "success",
                        title: "Perfecto",
                        text: "¡Acción ejecutada correctamente!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "gesUsuarios";
                        }
                    })

                    
                    </script>';
            }

        }
    }

    //INSERTAR LOS DATOS DEL USUARIO EN LA TABLA datos_usuario
    static public function ctrDatosUsuario(){
        //comprobamos los datos que pasan
        if (isset($_POST["nombre"]) && isset($_POST["Apellido1"]) && isset($_POST["dni"])
        && isset($_POST["telefono"]) && isset($_POST["direccion"]) && isset($_POST["poblacion"]) && isset($_POST["provincia"])
        && isset($_POST["pais"]) && isset($_POST["MonedaFondos"]) && isset($_POST["nusuario"])) {
            //creamos las variables y array para su insercion
            $tabla = "datos_usuario";
            $item = "n_usuario";
            $datos = array(
                "dni" => strtoupper($_POST["dni"]),
                "nombre_u" => strtoupper($_POST["nombre"]),
                "apellido1" => strtoupper($_POST["Apellido1"]),
                "apellido2" => strtoupper($_POST["Apellido2"]),
                "telefono" => $_POST["telefono"],
                "direccion" => strtoupper($_POST["direccion"]),
                "poblacion" => strtoupper($_POST["poblacion"]),
                "provincia" => strtoupper($_POST["provincia"]),
                "pais" => strtoupper($_POST["pais"]),
                "n_usuario" => $_POST["nusuario"]
            );
            $datos2 = array(
                "n_fondos" => 0,
                "moneda_fondo" => $_POST["MonedaFondos"],
                "n_usuario" => $_POST["nusuario"]
            );
            //buscamos el usuario
            $DTUsuario = ModeloUsuarios::MdlComprobarDatosUsuarios($item,$_POST["nusuario"]);
            //comprobamos el usuario
            if ($DTUsuario=="ok") {
                //mostramos los fondos del usuario
                $fondos_Usuario = ModeloUsuarios::MdlMostrarFUsuario($_POST["nusuario"]);
                //comprobamos la moneda de uso
                if ($fondos_Usuario['moneda_fondo']!=$_POST["MonedaFondos"]) {
                    //si es distinta la cambiamos y actualizamos los fondos
                    $url="https://rest.coinapi.io/v1/exchangerate/".$fondos_Usuario['moneda_fondo']."/".$_POST["MonedaFondos"];
                    $respuestaApi=ControladorMonedas::ctrMostrarDAPI($url);
                    $fondos=($respuestaApi['rate']*$fondos_Usuario['n_fondos'])/1;
                    $datos2 = array(
                        "n_fondos" => $fondos,
                        "moneda_fondo" => $_POST["MonedaFondos"],
                        "n_usuario" => $_POST["nusuario"]
                        
                    );
                    $respuestaF=ModeloUsuarios::MdlUpdateDatosFondos($datos2);
                }
                //actualizamos los datos del usuario
                $respuestaDU=ModeloUsuarios::MdlUpdateDatosUsuarios($datos);
                if($respuestaDU == "ok"){
                    echo '<script>
                
                        Swal.fire({
                            icon: "success",
                            title: "Perfecto",
                            text: "¡Acción ejecutada correctamente!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "perfil";
                            }
                        })
    
                        
                        </script>';
                }
            }else{
                //INSERTAR LOS DATOS EN LA TABLA DATOS USUARIO, FONDOS Y VALIDACIONES
                //buscamos al usuario
                $respuesta2 =ModeloUsuarios::MdlMostrarUsuarios("datos_usuario","dni",$_POST["dni"]);
                if ($respuesta2==null) {
                    //insertamos todos los datos en la tabla datos_usuario y fondos
                    $respuestaInsertarDatos = ModeloUsuarios::MdlIngresarDatosUsuario($datos);
                    $respuestaInsertarfondos = ModeloUsuarios::MdlInsertarTuplaFondos($datos2);
                    $mostrarValidaciones=ModeloUsuarios::MdlMostrarUsuarios('validaciones','n_usuario',$_SESSION["usuario"]);
                    if ($mostrarValidaciones!=null) {
                        $urlValidacion="UPDATE validaciones SET validado=0 WHERE n_usuario='".$_SESSION["usuario"]."'";
                        $respuestaInsertarvalidez=ModeloUsuarios::MdlSentenciatUniversal($urlValidacion);
                    }else {
                        $respuestaInsertarvalidez = ModeloUsuarios::MdlInsertarValidacion(0,$_POST["nusuario"]);
                    }
                    
                    if ($respuestaInsertarDatos=='ok' &&$respuestaInsertarfondos=='ok' && $respuestaInsertarvalidez=='ok') {
                        echo '<script>
                    
                            Swal.fire({
                                icon: "success",
                                title: "Perfecto",
                                text: "¡Acción ejecutada correctamente! Solo hay que esperar que un usuario administrador valide los datos",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "perfil";
                                }
                            })
        
                            
                            </script>';
                    }
                }else {
                    echo '<script>
                    
                            Swal.fire({
                                icon: "error",
                                title: "Oooops",
                                text: "¡El DNI ya existe, no hagas trampa",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "perfil";
                                }
                            })
        
                            
                            </script>';
                }
                
            }

        }
    }
    //ACTUALIZAR LA CONTRASEÑA O EMAIL DENTRO DE LA APLICACION
    static public function ctrDatosCuentaUsuario(){
        
        if (isset($_POST["passEditar"]) && isset($_POST["pass2Editar"]) && isset($_POST["emailEditar"])
        && isset($_POST["nusuarioEditar"])) {
            if ($_POST["passEditar"]==$_POST["pass2Editar"]) {
                
                if (filter_var($_POST["emailEditar"], FILTER_VALIDATE_EMAIL)) {
                
                    $tabla = "usuarios";
                    $item = "n_usuario";
                    $datos = array(
                        "pass" => password_hash($_POST["passEditar"],PASSWORD_DEFAULT),
                        "email" => $_POST["emailEditar"],
                        "n_usuario" => $_POST["nusuarioEditar"]
                    );
                    $respuestaUpdateCuentaUsuario=ModeloUsuarios::MdlUpdateDatosCuentaUsuario($datos);
                    if ($respuestaUpdateCuentaUsuario=="ok") {
                        echo '<script>
                
                        Swal.fire({
                            icon: "success",
                            title: "Perfecto",
                            text: "¡Acción ejecutada correctamente!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "perfil";
                            }
                        })
    
                        
                        </script>';
                    }
                }else{
                    echo '<script>
                    
                            Swal.fire({
                                icon: "error",
                                title: "Oooops",
                                text: "¡El email no tiene un formato adecuado!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "perfil";
                                }
                            })
        
                            
                            </script>';
                }
            }else{
                echo '<script>
                
                        Swal.fire({
                            icon: "error",
                            title: "Oooops",
                            text: "¡Las contraseñas no corresponden!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "perfil";
                            }
                        })
    
                        
                        </script>';
            }
            
        }elseif (isset($_POST["emailEditar"]) && isset($_POST["nusuarioEditar"])) {
            if (filter_var($_POST["emailEditar"], FILTER_VALIDATE_EMAIL)) {
                
                $urlActualizarEmail="UPDATE usuarios SET email='".$_POST["emailEditar"]."' WHERE n_usuario='".$_POST["nusuarioEditar"]."'";
                $respuestaUpdateEmailUsuario=ModeloUsuarios::MdlSentenciatUniversal($urlActualizarEmail);
                if ($respuestaUpdateEmailUsuario=="ok") {
                    echo '<script>
            
                    Swal.fire({
                        icon: "success",
                        title: "Perfecto",
                        text: "¡Acción ejecutada correctamente!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "perfil";
                        }
                    })

                    
                    </script>';
                }
            }else{
                echo '<script>
                
                        Swal.fire({
                            icon: "error",
                            title: "Oooops",
                            text: "¡El email no tiene un formato adecuado!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "perfil";
                            }
                        })
    
                        
                        </script>';
            }
        }
    }
    //COMPROBAR USUARIO PREVIO AL RESETEO DE CONTRASEÑA
    static public function ctrComprobarUsuario()
    {
        //comprobamos los datos que mandan
        if (isset($_POST["nombre_usuario"])&&isset($_POST["email_usuario"])) {
            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["nombre_usuario"])) {
                $tabla = "usuarios";
                $item = "n_usuario";
                $valor = $_POST["nombre_usuario"];
                //buscamos el usuario
                $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla, $item, $valor);
                if (isset($respuesta["n_usuario"])) {
                    //redireccionamos a la pantalla de cambio de contraseña
                    if ($_POST["email_usuario"]==$respuesta["email"]) {
                        echo '<script>
                        
                                window.location="index.php?ruta=cambioPass&nombre_usuario='.$_POST["nombre_usuario"].'";
                            
                            </script>';
                    }else {
                        echo '</br><div class="alert alert-danger">El correo electronico no coincide con el usuario</div>';
                    }
                    
                }else {
                    echo '</br><div class="alert alert-danger">Error de usuario</div>';
                }
                
            }
        }
    }
    //RESETEAR CONTRASEÑA DESDE LA OPCION DE HE OLVIDADO LA CONTRASEÑA
    static public function ctrActualizarPassUsuario()
    {
        //comprobamos los datos
        if (isset($_GET['nombre_usuario'])&&isset($_POST["password"])&&isset($_POST["password2"])) {
            //comprobamos que coincinden las contraseñas
            if ($_POST["password"]==$_POST["password2"]) {
                
                $datos = array(
                    "pass" => password_hash($_POST["password"],PASSWORD_DEFAULT),
                    "n_usuario" => $_POST["nombre_usuarioC"]
                );
                //actualizamos las contraseñas
                $respuestaUpdatePass=ModeloUsuarios::MdlUpdatePassUsuario($datos);
                if ($respuestaUpdatePass=="ok") {
                    echo '<script>
                    
                            Swal.fire({
                                icon: "success",
                                title: "Perfecto",
                                text: "¡Acción ejecutada correctamente!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "login";
                                }
                            })
        
                            
                            </script>';
                }else{
                    echo '<script>
                    
                            Swal.fire({
                                icon: "error",
                                title: "Ooops",
                                text: "¡Acción no ejecutada correctamente!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "login";
                                }
                            })
        
                            
                            </script>';
                }
            }
            
        }
    }

    //VALIDAR USUARIO
    static public function ctrValidarUsuario()
    {
        //comprobamos que nos pasan un usuario
        if (isset($_POST['Vnusuario'])) {
            $tabla = "usuarios";
            $item="n_usuario";
            //buscamos al usuario
            $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$_POST['Vnusuario']);
            //comprobamos si existe
            if ($respuesta!=null) {
                //actualizamos el usuario
                $urlUsuario="UPDATE usuarios SET rol='Usuario' WHERE n_usuario='".$_POST['Vnusuario']."'";
                $urlValidacion="UPDATE validaciones SET validado=1 WHERE n_usuario='".$_POST['Vnusuario']."'";
                $respuestaUsuario=ModeloUsuarios::MdlSentenciatUniversal($urlUsuario);
                $respuestaValidacion=ModeloUsuarios::MdlSentenciatUniversal($urlValidacion);
                if ($respuestaUsuario=='ok' && $respuestaValidacion=='ok') {
                    echo '<script>
                            
                                    Swal.fire({
                                        icon: "success",
                                        title: "Perfecto",
                                        text: "¡Acción ejecutada correctamente!",
                                        confirmButtonText: "Cerrar"
                                    }).then((result)=>{
                                        if(result.value){
                                            window.location = "gesUsuarios";
                                        }
                                    })
                
                                    
                                    </script>';
                }
            }else {
                echo '<script>
                                
                        Swal.fire({
                            icon: "error",
                            title: "Oooops",
                            text: "¡Acción no ejecutada correctamente!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "gesUsuarios";
                            }
                        })

                    
                    </script>';
            }
            
        }
        
    }
    //EDITAR LOS DATOS DE UN USUARIO
    static public function ctrActualizarDatosUsuario(){
        //comprobamos los datos que mandan
        if (isset($_POST["Enombre"]) && isset($_POST["Eapellido1"]) && isset($_POST["Edni"])
        && isset($_POST["Etelefono"]) && isset($_POST["Edireccion"]) && isset($_POST["Epoblacion"]) && isset($_POST["Eprovincia"])
        && isset($_POST["Epais"]) && isset($_POST["Emoneda"]) && isset($_POST["Enusuario"])) {
            
            $tabla = "datos_usuario";
            $item = "n_usuario";
            $datos = array(
                "dni" => strtoupper($_POST["Edni"]),
                "nombre_u" => strtoupper($_POST["Enombre"]),
                "apellido1" => strtoupper($_POST["Eapellido1"]),
                "apellido2" => strtoupper($_POST["Eapellido2"]),
                "telefono" => $_POST["Etelefono"],
                "direccion" => strtoupper($_POST["Edireccion"]),
                "poblacion" => strtoupper($_POST["Epoblacion"]),
                "provincia" => strtoupper($_POST["Eprovincia"]),
                "pais" => strtoupper($_POST["Epais"]),
                "n_usuario" => $_POST["Enusuario"]
            );
            $datos2 = array(
                "n_fondos" => 0,
                "moneda_fondo" => $_POST["Emoneda"],
                "n_usuario" => $_POST["Enusuario"]
            );
            //buscamos el usuario
            $DTUsuario = ModeloUsuarios::MdlComprobarDatosUsuarios($item,$_POST["Enusuario"]);
            
            if ($DTUsuario=="ok") {
                //buscamos los fondos
                $fondos_Usuario = ModeloUsuarios::MdlMostrarFUsuario($_POST["Enusuario"]);
                if ($fondos_Usuario['moneda_fondo']!=$_POST["Emoneda"]) {
                    //actualizamos todo
                    $url="https://rest.coinapi.io/v1/exchangerate/".$fondos_Usuario['moneda_fondo']."/".$_POST["Emoneda"];
                    $respuestaApi=ControladorMonedas::ctrMostrarDAPI($url);
                    $fondos=($respuestaApi['rate']*$fondos_Usuario['n_fondos'])/1;
                    $datos2 = array(
                        "n_fondos" => $fondos,
                        "moneda_fondo" => $_POST["Emoneda"],
                        "n_usuario" => $_POST["Enusuario"]
                        
                    );
                    $respuestaF=ModeloUsuarios::MdlUpdateDatosFondos($datos2);
                }
                $respuestaDU=ModeloUsuarios::MdlUpdateDatosUsuarios($datos);
                if($respuestaDU == "ok"){
                    echo '<script>
                
                        Swal.fire({
                            icon: "success",
                            title: "Perfecto",
                            text: "¡Acción ejecutada correctamente!",
                            confirmButtonText: "Cerrar"
                        }).then((result)=>{
                            if(result.value){
                                window.location = "gesUsuarios";
                            }
                        })
    
                        
                        </script>';
                }
            }



        }
    }
    //BORRAR USUARIO
    static public function ctrBorrarUsuario(){
        if (isset($_GET["Eusuario"])) {
            
            $tabla = "usuarios";
            $datos = $_GET["Eusuario"];
            //ejecutamos la funcion de eliminar usuario
            $respuesta = ModeloUsuarios::MdlBorrarUsuario($tabla,$datos);

            if($respuesta == "ok"){
                echo '<script>
            
                    Swal.fire({
                        icon: "success",
                        title: "Perfecto",
                        text: "¡Usuario eliminado correctamente!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "gesUsuarios";
                        }
                    })

                    
                    </script>';
            }

        }
    }

}
