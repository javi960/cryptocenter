<?php

class ControladorFondos
{
    /**
     * CARGAR FONDOS
     * funcion para cargar fondos en el usuario
     * 
     */
    static public function ctrCargarFondos()
    {
        //comprobamos que los datos que pedimos se pasan
        if (isset($_POST["AFondos"])&&isset($_POST["nombreTarjeta"])&&isset($_POST["numeroTarjeta"])
        &&isset($_POST["CVC"])&&isset($_POST["nusuario"])) {
            //buscamos los fondos que tiene el usuario en la tabla fondos
            $fondos_Usuario = ModeloUsuarios::MdlMostrarFUsuario($_POST["nusuario"]);
            //creamos el array para la actualizacion de los fondos
            $datos = array(
                "n_fondos" => $fondos_Usuario['n_fondos']+$_POST["AFondos"],
                "n_usuario" => $_POST["nusuario"]
            );
            //creamos la variable para saber la fecha de hoy
            $hoy = getdate();
            //creamos la fecha
            $fecha=$hoy["mday"]."/".$hoy["mon"]."/".$hoy["year"]." ".$hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
            //creamos el array para la tabla registro de fondos
            $datos2 = array(
                "accion" => "cargar",
                "cantidad_fondo" => $_POST["AFondos"],
                "fecha" => strval($fecha),
                "id_usuario" => $_POST["nusuario"]
            );
            //actualizamos los fondos
            $respuesta=ModeloFondos::MdlUpdateFondos($datos);
            //insertamos los datos en la tabla registro_fondos
            $respuesta2=ModeloFondos::MdlInsertarRegistroFondos($datos2);
            if ($respuesta=="ok" && $respuesta2=="ok") {
                //si todo ha ido bien mostramos mensaje de ejecutado
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
        }
    }
    /**
     * RETIRAR FONDOS
     * funcion para retirar fondos en el usuario
     * 
     */
    static public function ctrRetirarFondos()
    {
        //comprobamos que los datos que pedimos se pasan
        if (isset($_POST["RFondos"])&&isset($_POST["numeroRTarjeta"])&&isset($_POST["nusuario"])) {
            $fondos_Usuario = ModeloUsuarios::MdlMostrarFUsuario($_POST["nusuario"]);
            //comprobamos que los fondos que tenemos son mas que los que quiere retirar
            if ($fondos_Usuario['n_fondos']>=$_POST["RFondos"]) {
                //creamos el array para la actualizacion de los fondos
                $datos = array(
                    "n_fondos" => $fondos_Usuario['n_fondos']-$_POST["RFondos"],
                    "n_usuario" => $_POST["nusuario"]
                );
                //creamos la variable para saber la fecha del dia
                $hoy = getdate();
                //creamos la fecha actual
                $fecha=$hoy["mday"]."/".$hoy["mon"]."/".$hoy["year"]." ".$hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
                //creamos el array para insertar los datos en la tabla registro_fondos
                $datos2 = array(
                    "accion" => "retirar",
                    "cantidad_fondo" => $_POST["RFondos"],
                    "fecha" => strval($fecha),
                    "id_usuario" => $_POST["nusuario"]
                );
                //actualizamos los fondos
                $respuesta=ModeloFondos::MdlUpdateFondos($datos);
                //insertamos el registro de fondos
                $respuesta2=ModeloFondos::MdlInsertarRegistroFondos($datos2);
                if ($respuesta=="ok" && $respuesta2=="ok") {
                    //si todo ha ido bien mostrar mensaje de ejecutado y redireccionar
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
            }else {
                //si se quieren retirar mas fondos de los que posee el usuario mostrar mensaje de error y redireccionar
                echo '<script>
                    
                            Swal.fire({
                                icon: "error",
                                title: "Ooops",
                                text: "¡La cantidad marcada es mayor a la que tienes!",
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