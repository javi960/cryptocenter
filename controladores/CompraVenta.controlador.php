<?php

class ControladorCompraVenta
{
    /**
     * COMPRAR MONEDAS
     * Funcion para realizar la compra de las monedas
     */

    static public function ctrComprarMonedas()
    {
        //coprobamos si se han pasado los datos del dinero a gastar, la moneda y el nombre del usuario
        if (isset($_POST["CanjeMoneda"])&&isset($_POST["idmoneda"])&&isset($_POST["nusuario"])) {
            //creamos la variable hoy para saber la fecha actual
            $hoy = getdate();
            //creamos la variable donde se guardará la fecha de hoy segun el sistema
            $fecha=$hoy["mday"]."/".$hoy["mon"]."/".$hoy["year"]." ".$hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
            //llamamos a la funcion de la clase modelo usuario para saber los fondos del usuario
            $respuestaUsuario=ModeloUsuarios::MdlMostrarFUsuario($_POST["nusuario"]);
            //Comprobamos que los fondos son mayores o iguales a los que quiere gastar el usuario
            if ($respuestaUsuario["n_fondos"]>=floatval($_POST["CanjeMoneda"])) {
                //si hay suficientes fondos
                //extraemos los datos de la moneda desde la tabla de monedas
                $respuestaMonedas=ModeloMoneda::MdlMostrarMoneda("monedas","id_moneda",$_POST['idmoneda']);
                //calculamos los beneficios de la plataforma segun lo almacenado en la tabla monedas
                $beneficioPlataforma=floatval($_POST["CanjeMoneda"])*($respuestaMonedas['BenfCompra']/100);
                //creamos la url para usarla en la api y conocer el valor actual de la moneda a comprar
                $url="https://rest.coinapi.io/v1/exchangerate/".$_POST["idmoneda"]."/".$respuestaUsuario["moneda_fondo"];
                $respuestaAPI=ControladorMonedas::ctrMostrarDAPI($url);
                //calculamos el resultado final del beneficio
                $resultadoBeneficio=floatval($_POST["CanjeMoneda"])-$beneficioPlataforma;
                //creamos arrays para cada interaccion de la base de datos y los inicializamos con los datos necesarios
                //este array será para la tabla compra_venta en la cual insertaremos la accion que se ha realizado
                //con los datos para su tratamiento en futuras interacciones
                $datos = array(
                    "moneda" => $_POST["idmoneda"],
                    "valor" => floatval($respuestaAPI["rate"]),
                    "fecha_c" => $fecha,
                    "t_accion" => "comprar",
                    "cantidad" => $resultadoBeneficio/floatval($respuestaAPI["rate"]),
                    "dinero" => $resultadoBeneficio,
                    "n_usuario" => $_POST["nusuario"]
                );
                //este array será para comprobar la monedas que sera comprada en la tabla monedas_cartera
                $datos2 = array(
                    "cantidad" =>$resultadoBeneficio/floatval($respuestaAPI["rate"]),
                    "id_moneda" =>  $_POST["idmoneda"],
                    "n_usuario" => $_POST["nusuario"]
                );
                //este array es para actualizar los fondos que le quedan al usuario
                $datos3= array(
                    "n_fondos" =>$respuestaUsuario["n_fondos"]-$resultadoBeneficio,
                    "n_usuario" => $_POST["nusuario"]
                );
                //este array es para hacer una busqueda posteriormente para los beneficios de la plataforma
                $datosSelect = array(
                    "fecha_c" =>$fecha,
                    "moneda" =>  $_POST["idmoneda"],
                    "n_usuario" => $_POST["nusuario"]
                );
                //comprobamos si la moneda a comprar existe en la tabla
                $respuestaMonedaCartera=ModeloCompraVenta::MdlSelectMonedasCartera($datos2);
                //si existe entramos para actualizar los datos
                if ($respuestaMonedaCartera!=null) {
                    //modificamos los datos del array datos2 con los que hay que insertar
                    $datos2 = array(
                        "cantidad" =>floatval($respuestaMonedaCartera['cantidad'])+($resultadoBeneficio/floatval($respuestaAPI["rate"])),
                        "id_moneda" =>  $_POST["idmoneda"],
                        "n_usuario" => $_POST["nusuario"]
                    );
                    //llamamos a la funcion de actualizar monedas
                    $respuestaUpdateMonedas=ModeloCompraVenta::MdlUpdateMonedasCartera($datos2);
                    //llamamos a la funcion de insertar registro en la tabla compra_venta
                    $respuestaInsertarRegistroMonedas=ModeloCompraVenta::MdlInsertarRegistroCompraVenta($datos);
                    //llamamos a la funcion de actualizar fondos
                    $respuestaRestarFondos=ModeloFondos::MdlUpdateFondos($datos3);
                    //comprobamos que las tres interacciones han salido bien
                    if ($respuestaUpdateMonedas=="ok" && $respuestaInsertarRegistroMonedas=="ok" &&$respuestaRestarFondos=='ok') {
                        //buscamos la tubola recien insertada en la tabla compra_venta
                        $respuestaFilaCompraVenta=ModeloCompraVenta::MdlSelectIdCompraVenta($datosSelect);
                        //creamos un nuevo array con el beneficio de esta compra
                        $datosBeneficios= array(
                            "id_compraVenta" =>$respuestaFilaCompraVenta["id"],
                            "cantidad" => $beneficioPlataforma
                        );
                        //insertamos en la tabla de beneficios
                        $respuestaInsertarBeneficio=ModeloCompraVenta::MdlInsertarBeneficios($datosBeneficios);
                        //si todo va bion mostramos un mensaje de accion ejecutada correctamente y recargamos
                        if ($respuestaInsertarBeneficio=="ok") {
                            echo '<script>
                    
                            Swal.fire({
                                icon: "success",
                                title: "Perfecto",
                                text: "¡Acción ejecutada correctamente!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "compras";
                                }
                            })
        
                            
                            </script>';
                        }
                        
                    }
                }else {
                    //en caso de que la moneda no exista en la cartera
                    //llamamos a la funcion de insertar moneda en la cartera
                    $respuestaInsertarMonedas=ModeloCompraVenta::MdlInsertarMonedasCartera($datos2);
                    //llamamos a la funcion de insertar registro en la tabla compra_venta
                    $respuestaInsertarRegistroMonedas=ModeloCompraVenta::MdlInsertarRegistroCompraVenta($datos);
                    //llamamos a la funcion de actualizar fondos
                    $respuestaRestarFondos=ModeloFondos::MdlUpdateFondos($datos3);
                    //comprobamos que las tres interacciones han salido bien
                    if ($respuestaInsertarMonedas=="ok" && $respuestaInsertarRegistroMonedas=="ok" &&$respuestaRestarFondos=='ok') {
                        //buscamos la tubola recien insertada en la tabla compra_venta
                        $respuestaFilaCompraVenta=ModeloCompraVenta::MdlSelectIdCompraVenta($datosSelect);
                        //creamos un nuevo array con el beneficio de esta compra
                        $datosBeneficios= array(
                            "id_compraVenta" =>$respuestaFilaCompraVenta["id"],
                            "cantidad" => $beneficioPlataforma
                        );
                        //insertamos en la tabla de beneficios
                        $respuestaInsertarBeneficio=ModeloCompraVenta::MdlInsertarBeneficios($datosBeneficios);
                        //si todo va bion mostramos un mensaje de accion ejecutada correctamente y recargamos
                        if ($respuestaInsertarBeneficio=="ok") {
                            echo '<script>
                    
                            Swal.fire({
                                icon: "success",
                                title: "Perfecto",
                                text: "¡Acción ejecutada correctamente!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "compras";
                                }
                            })
        
                            
                            </script>';
                        }
                    }
                }
            }else {
                //si no hay fondos muestra este mensaje
                echo '<script>
                    
                            Swal.fire({
                                icon: "error",
                                title: "Ooops",
                                text: "¡La cantidad marcada es mayor a la que tienes! "'.$respuestaUsuario["n_fondo"].',
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "compras";
                                }
                            })
        
                            
                            </script>';
            }
            
        }
    }

    /**
     * VENDER MONEDAS
     * Funcion para realizar la venta de las monedas
     */
    static public function ctrVenderMonedas()
    {
        //comprobar si los datos de la cantidad de la moneda a vender el usuario y la moneda existen
        if (isset($_POST["VentaMoneda"])&&isset($_POST["Vnusuario"])&&isset($_POST["Vidmoneda"])) {
            //creamos la variable hoy para saber la fecha actual
            $hoy = getdate();
            //creamos la variable donde se guardará la fecha de hoy segun el sistema
            $fecha=$hoy["mday"]."/".$hoy["mon"]."/".$hoy["year"]." ".$hoy["hours"].":".$hoy["minutes"].":".$hoy["seconds"];
            //array para comprobar la moneda
            $datos= array(
                "n_usuario" =>$_POST["Vnusuario"],
                "id_moneda" => $_POST["Vidmoneda"]
            );
            //llamamos a la funcion de buscar moneda y pasamos el array que acabamos de crear
            $respuestaCartera=ModeloCompraVenta::MdlSelectMonedasCartera($datos);
            //comprobamos que la cantidad de monedas en la cartera sea mayor o igual a la que se quiere vender
            if ($respuestaCartera['cantidad']>=$_POST["VentaMoneda"]) {
                //si la cantidad que tenemos es mayor o igual entramos en esta zona
                $resta=$respuestaCartera['cantidad']-$_POST["VentaMoneda"];//resultado de monedas que me quedan y hay que subir a la tabla monedas cartera
                $respuestaVUsuario=ModeloUsuarios::MdlMostrarFUsuario($_POST["Vnusuario"]);//extraigo los datos de la tabla fondos
                $respuestaMonedas=ModeloMoneda::MdlMostrarMoneda("monedas","id_moneda",$_POST['Vidmoneda']);//extraigo el beneficio de esa moneda                
                $url="https://rest.coinapi.io/v1/exchangerate/".$_POST["Vidmoneda"]."/".$respuestaVUsuario["moneda_fondo"];//saber el valor en la moneda del usuario de esa moneda
                $respuestaAPI=ControladorMonedas::ctrMostrarDAPI($url);//ya tengo el valor de esa moneda
                $dineroTotal=$respuestaAPI['rate']*$_POST["VentaMoneda"];
                $beneficioVPlataforma=floatval($dineroTotal)*($respuestaMonedas['BenfVenta']/100);//dinero para la plataforma
                $dineroReal=$dineroTotal-$beneficioVPlataforma;//dinero para el usuario
                $datosCV = array(
                    "moneda" => $_POST["Vidmoneda"],
                    "valor" => floatval($respuestaAPI["rate"]),
                    "fecha_c" => $fecha,
                    "t_accion" => "vender",
                    "cantidad" => $_POST["VentaMoneda"],
                    "dinero" => $dineroReal,
                    "n_usuario" => $_POST["Vnusuario"]
                );

                $datosMonedaCartera = array(
                    "cantidad" =>$resta,
                    "id_moneda" =>  $_POST["Vidmoneda"],
                    "n_usuario" => $_POST["Vnusuario"]
                );
                
                $datosFondos= array(
                    "n_fondos" =>$respuestaVUsuario["n_fondos"]+$dineroReal,
                    "n_usuario" => $_POST["Vnusuario"]
                );

                $datosSelectCV = array(
                    "fecha_c" =>$fecha,
                    "moneda" =>  $_POST["Vidmoneda"],
                    "n_usuario" => $_POST["Vnusuario"]
                );
                //actualizamos las monedas de la tabla monedas_cartera
                $respuestaVUpdateMonedas=ModeloCompraVenta::MdlUpdateMonedasCartera($datosMonedaCartera);
                //insertamos los datos en la tabla compraventa
                $respuestaVInsertarRegistroMonedas=ModeloCompraVenta::MdlInsertarRegistroCompraVenta($datosCV);
                //actualizamos los fondos que al usuario le quedan
                $respuestaVRestarFondos=ModeloFondos::MdlUpdateFondos($datosFondos);
                //comprobamos que las tres interaciones han sido correctas
                if ($respuestaVUpdateMonedas=="ok" && $respuestaVInsertarRegistroMonedas=="ok" &&$respuestaVRestarFondos=='ok') {
                    //buscamos la tupla recien creada en la tabla compra venta
                    $respuestaVFilaCompraVenta=ModeloCompraVenta::MdlSelectIdCompraVenta($datosSelectCV);
                    //creamos el array con el id de la interacion anterior    
                    $datosVBeneficios= array(
                            "id_compraVenta" =>$respuestaVFilaCompraVenta["id"],
                            "cantidad" => $beneficioVPlataforma
                        );
                        //insertamos los datos en la tabla beneficios
                        $respuestaVInsertarBeneficio=ModeloCompraVenta::MdlInsertarBeneficios($datosVBeneficios);
                        if ($respuestaVInsertarBeneficio=="ok") {
                            //si todo ha salido bien se muestra el mensaje de accion ejecutada correctamente y se redirecciona
                            echo '<script>
                    
                            Swal.fire({
                                icon: "success",
                                title: "Perfecto",
                                text: "¡Acción ejecutada correctamente!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "ventas";
                                }
                            })
        
                            
                            </script>';
                        }
                }
                
            }else {
                // si la cantidad es mayor mostramos mensaje de error
                echo '<script>
                    
                            Swal.fire({
                                icon: "error",
                                title: "Ooops",
                                text: "¡La cantidad marcada es mayor a la que tienes!",
                                confirmButtonText: "Cerrar"
                            }).then((result)=>{
                                if(result.value){
                                    window.location = "ventas";
                                }
                            })
        
                            
                            </script>';
            }
            
        }
    }
}