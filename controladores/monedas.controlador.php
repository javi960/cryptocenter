<?php

class ControladorMonedas
{

    /**
     * MOSTRAR TODAS LAS MONEDAS DISPONIBLES DESDE LA API
     * funcion para listar todas las monedas de la api y guardarlas en un json
     * 
     */
    static public function ctrMostrarMonedas(){
        //comprobamos si existe el fichero datosMonedasApi.json
        if (file_exists("vistas/json/datosMonedasApi.json")) {
            //si existe comprobamos si no se ha actualizado en la ultima hora y el dia
            if (date ("H", filemtime("vistas/json/datosMonedasApi.json"))!=date("H") && date ("d", filemtime("vistas/json/datosMonedasApi.json"))!=date("d")) {
                //si el fichero es mas antiguo de la hora actual lo creamos y lo retornamos
                $json = json_encode(file_get_contents("https://rest.coinapi.io/v1/assets?apikey=255AEDF5-A559-4623-9F4C-971E4B506D89&invert=true&output_format=json"));
                $bytes = file_put_contents("vistas/json/datosMonedasApi.json", $json);
                $data = file_get_contents("vistas/json/datosMonedasApi.json");
                $decodificar = json_decode($data);
                $res=json_decode($decodificar);
                return $res;
            }else {
                //retornamos el fichero
                $data = file_get_contents("vistas/json/datosMonedasApi.json");
                $decodificar = json_decode($data);
                $res=json_decode($decodificar);
                return $res;
            }
        } else {
            //creamos el fichero y lo retornamos
            $json = json_encode(file_get_contents("https://rest.coinapi.io/v1/assets?apikey=255AEDF5-A559-4623-9F4C-971E4B506D89&invert=true&output_format=json"));
            $bytes = file_put_contents("vistas/json/datosMonedasApi.json", $json);
            $data = file_get_contents("vistas/json/datosMonedasApi.json");
            $decodificar = json_decode($data);
            $res=json_decode($decodificar);
            return $res;
        }
    }
    /**
     * MOSTRAR TODOS LOS ICONOS DE LAS MONEDAS DISPONIBLES DESDE LA API
     * funcion para listar todos los iconos de las monedas de la api y guardarlas en un json
     * 
     */
    static public function ctrMostrarIconosMonedas(){
        //comprobamos si existe el fichero datosIconosMonedasApi.json
        if (file_exists("vistas/json/datosIconosMonedasApi.json")) {
            //comprobamos que el dia del fichero es distinto
            if (date ("d", filemtime("vistas/json/datosIconosMonedasApi.json"))!=date("d")) {
                //si es distinto lo creamos de nuevo y lo retornamos
                $json = json_encode(file_get_contents("https://rest.coinapi.io/v1/assets/icons/32?apikey=255AEDF5-A559-4623-9F4C-971E4B506D89&invert=true&output_format=json"),true);
                $bytes = file_put_contents("vistas/json/datosIconosMonedasApi.json", $json);
                $data = file_get_contents("vistas/json/datosIconosMonedasApi.json");
                $decodificar = json_decode($data);
                $res=json_decode($decodificar);
                return $res;
            }else {
                //retornamos el fichero
                $data = file_get_contents("vistas/json/datosIconosMonedasApi.json");
                $decodificar = json_decode($data);
                $res=json_decode($decodificar);
                return $res;
            }
        } else {
            //si no existe los creamos y lo retornamos
            $json = json_encode(file_get_contents("https://rest.coinapi.io/v1/assets/icons/32?apikey=255AEDF5-A559-4623-9F4C-971E4B506D89&invert=true&output_format=json"),true);
            $bytes = file_put_contents("vistas/json/datosIconosMonedasApi.json", $json);
            $data = file_get_contents("vistas/json/datosIconosMonedasApi.json");
            $decodificar = json_decode($data);
            $res=json_decode($decodificar);
            return $res;
        }
    }
    /**
     * MOSTRAR CUALQUIER DATO DE LA API
     * funcion para hacer cualquier peticion a la api
     * 
     */
    static public function ctrMostrarDAPI($url){
        //pasamos la url y retornamos el resultado que nos mande la API
        //en el array se incluye la clave para el uso de la API
        $headers = array(
            "X-CoinAPI-Key: 255AEDF5-A559-4623-9F4C-971E4B506D89"
        );
        $ch = curl_init();//se inicia el curl
        curl_setopt($ch, CURLOPT_URL, $url);//se incluye la url
        curl_setopt($ch, CURLOPT_RESUME_FROM,"GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);//se incluye la clave
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $res = curl_exec($ch);//se ejecuta
        $res1 = json_decode($res, true);//se decodfica el json
        
        return $res1;//se retornan los datos
        curl_close($ch);//se cierra el curl
        
    }
    /**
     * MOSTRAR TODAS LAS MONEDAS DE LA TABLA MONEDAS
     * funcion para listar todas las monedas de la tabla monedas de la base de datos
     * 
     */
    static public function ctrMostrarMonedasBase($item,$valor){
        $tabla = "monedas";
        //llamamos a la funcion para mostrar la tabla monedas
        $respuesta = ModeloMoneda::MdlMostrarMoneda($tabla,$item,$valor);

        return $respuesta;
    }

    /*
        MOSTAR MONEDAS LISTA DE SEGUIMIENTO USUARIO
    */
    static public function ctrMostrarMonedasSUsuario($item,$valor){
        $tabla = "monedas_seguimiento";
        $respuesta = ModeloMoneda::MdlMostrarMonedaSUsuario($tabla,$item,$valor);

        return $respuesta;
    }
    static public function ctrMostrarMonedasSUsuario2($valor){
        $respuesta = ModeloMoneda::MdlMostrarMonedaSUsuario2($valor);

        return $respuesta;
    }
    /*
        MOSTAR MONEDAS DE LA TABLA MONEDAS_CARTERA
    */
    static public function ctrMostrarMonedasbilletera($valor){
        $respuesta = ModeloMoneda::MdlMostrarMonedabilletera($valor);

        return $respuesta;
    }
    static public function ctrMostrarCarteraMonedas($item,$valor){
        $respuesta = ModeloMoneda::MdlMostrarCarteraMoneda($item,$valor);

        return $respuesta;
    }

    /*
        MOSTAR BENEFICIOS DE LA PLATAFORMA
    */
    static public function ctrMostrarBeneficios($item){
        //creamos la variable para la fecha del dia
        $hoy = getdate();
        //extraemos el año
        $anyo="%".$hoy["year"]."%";
        //llamamos a la funcion de buscar los beneficios del año pasando el año
        $respuesta = ModeloMoneda::MdlMostrarBeneficios($item,$anyo);
        //creamos los arrays para los beneficios de compra y de venta tendran 12 valores uno por cada mes
        $arrayVentaBeneficio=[0,0,0,0,0,0,0,0,0,0,0,0];
        $arrayCompraBeneficio=[0,0,0,0,0,0,0,0,0,0,0,0];
        //creamos el array que se retornara con todos los datos
        $arrayCompleto=[];
        //hacemos un foreach para tratar los datos que nos vienen del retorno de los beneficios
        foreach ($respuesta as $key => $value) {
            //dividimos el campo fecha_c que viene            
            $fechaPartida=explode('/',$value['fecha_c']);
            //comprobamos si la accion es comprar o vender
            if ($value['t_accion']=="comprar") {
                //si es comprar esa tupla se inserta en el array de arraycomprabeneficio
                $arrayCompraBeneficio[intval($fechaPartida[1])-1]=$arrayCompraBeneficio[intval($fechaPartida[1])-1]+$value['Cbeneficio'];
                
            }else{
                //si es vender esa tupla se inserta en el array de arrayventabeneficio
                $arrayVentaBeneficio[intval($fechaPartida[1])-1]=$arrayVentaBeneficio[intval($fechaPartida[1])-1]+$value['Cbeneficio'];
                
            }
        }
        //insertamos ambos array en el array completo y lo retornamos
        array_push($arrayCompleto,$arrayCompraBeneficio,$arrayVentaBeneficio);
        return $arrayCompleto;
    }
    
    /*
        ACTIVAR O DESACTIVAR MONEDAS

    */

    static public function ctrActivarMonedas(){

        //comprobamos el si se manda un id de moneda
        if (isset($_POST['ideMoneda'])) {
            //creamos las variables y array para pasarlas por el metodo de activar monedas o ingresar
            $tabla = "monedas";
            $item = "id_moneda";
            $datos = array("id_moneda" => $_POST["ideMoneda"],
                            "nombre" => $_POST["nombreMoneda"],
                            "BenfCompra" => $_POST["beneficioCompra"],
                            "BenfVenta" => $_POST["BeneficioVenta"],
                            "activo" => "1");
            //buscamos la moneda para activarla
            $coin = ModeloMoneda::MdlMostrarMonedaAdmin($tabla,$item,$_POST["ideMoneda"]);
            if ($coin['activo'] == "0") {
                //si la moneda existe ya en la base de datos actulizamos sus beneficios y cambiamos el valor activo por 1
                $respuesta = ModeloMoneda::MdlActivarMonedas($tabla,$datos["id_moneda"],$datos["BenfCompra"],$datos["BenfVenta"],"1");
            }else {
                // si no existe insetamos la moneda en la base de datos
                $respuesta = ModeloMoneda::MdlIngresarMoneda($tabla,$datos);
            }
            // si todo ha ido bien se muestra un mensaje de ejecutado
            if($respuesta == "ok"){
                echo '<script>
            
                    Swal.fire({
                        icon: "success",
                        title: "Perfecto",
                        text: "¡Acción ejecutada correctamente!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "monedas";
                        }
                    })

                    
                    </script>';
            }
        }
    }

    /*
        SEGUIR O NO SEGUIR MONEDAS

    */

    static public function ctrSeguirMonedas(){
        //comporbamos que se está pasando un id de moneda
        if (isset($_GET["Nmoneda"])) {
            //creamos las variables para el uso en las tablas
            $esta=false;//para comprobar que la moneda está o no está
            $tabla = "monedas_seguimiento";
            $item = "n_usuario";
            $datos = array("id_moneda" => $_GET["Nmoneda"],
                            "n_usuario" => $_SESSION["usuario"]);
            //comprobamos las monedas de la lista de seguimiento del usuario
            $coins = ModeloMoneda::MdlMostrarMonedaSUsuario($tabla,$item,$datos["n_usuario"]);
            foreach ($coins as $key => $value) {
                if ($value["id_moneda"]==$datos["id_moneda"]) {
                    $esta=true;
                }
            }
            if ($esta == true ) {
                //si la moneda esta en la tabla
                $hayMonedas= ModeloMoneda::MdlMostrarCarteraMoneda($_GET["Nmoneda"],$_SESSION["usuario"]);
                //comprobamos que no tenga monedas
                if ($hayMonedas==null) {
                    //si no tiene monedas en la cartera se elimina la moneda
                    $respuesta = ModeloMoneda::MdlEliminarSMoneda($tabla,$datos);
                }else {
                    $respuesta="fallo en la eliminacion";
                }
                
            }else {
                //si la moneda no existe se inserta en la tabla monedas en seguimiento
                $respuesta = ModeloMoneda::MdlIngresarSMoneda($tabla,$datos);
            }

            if($respuesta == "ok"){
                echo '<script>
            
                    Swal.fire({
                        icon: "success",
                        title: "Perfecto",
                        text: "¡Acción ejecutada correctamente!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "mpUsuario";
                        }
                    })

                    
                    </script>';
            }else {
                //mensaje de error en caso de que no se pueda eliminar la moneda
                echo '<script>
            
                    Swal.fire({
                        icon: "error",
                        title: "Oooops...",
                        text: "¡Para realizar esta acción primero debe vender todas las monedas de esta Criptomoneda!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "mpUsuario";
                        }
                    })

                    
                    </script>';
            }

        }
    }
    /*
        DESCARGAR TODOS LOS ICONOS DE LAS MONEDAS EN EL SERVIDOR
    */
    static public function ctrDescargarIconoMonedas($valor,$url){
        //selecionamos la carpeta
        $carpeta="vistas/img/";
        //creamos el destino con el nombre y la extension
        $destino=$carpeta.substr($valor.".png",0);
        if (!file_exists($destino)) {//comprobamos si existe, en el caso de no existir ejecutamos
            file_put_contents($destino, file_get_contents($url));
        }
    }
    /*
        MUESTRA LOS BENEFICIOS QUE HA CONSEGUIDO UN USUARIO POR LA VENTA DE CRIPTOMONEDAS
    */
    static public function ctrObligacionesFiscales($usuario){
        $hoy = getdate();
        $anyo="%".$hoy["year"]."%";
        $datosBilletera=ModeloCompraVenta::MdlSelectTodasMonedasCartera($usuario);
        $datosArray= array();
        
        $monedaF='';
        foreach ($datosBilletera as $key => $value) {
            $datosAux= array();
            $monedaF=$value["id_moneda"];
            $datosSelect = array(
                "moneda" =>  $value["id_moneda"],
                "n_usuario" => $usuario,
                "anyo" => $anyo
            );
            $datosCompra=ModeloCompraVenta::MdlSelectCompra($datosSelect);
            $datosVenta=ModeloCompraVenta::MdlSelectVenta($datosSelect);
            if ($datosVenta!=null) {
                $arrayValor1=array();
                $arrayValor2=array();
                $array1 = array();
                $array2 = array();
                foreach ($datosCompra as $key => $value) {
                    array_push($arrayValor1, $value['valor']);
                    array_push($array1, $value['cantidad']);
                }
                foreach ($datosVenta as $key => $value) {
                    array_push($arrayValor2, $value['valor']);
                    array_push($array2, $value['cantidad']);
                }
                $buclecontarray2=0;
                $beneficioasumar=0;
                $beneficioarestar=0;
                $resto=0;
                $restoMonedasVender=0;
                $restoValor=0;
                for ($i=0; $i <count($array1); $i++) {
                    if ($restoMonedasVender>0) {
            
                        if ($array1[$i]>$restoMonedasVender) {
                    
                            $resto=$array1[$i]-$restoMonedasVender;
                            
                            if ($restoValor>$arrayValor1[$i]) {
                                $beneficioasumar=$beneficioasumar+($restoMonedasVender*($restoValor-$arrayValor1[$i]));
                                
                                
                            }else {
                                $beneficioarestar=$beneficioarestar+($restoMonedasVender*($restoValor-$arrayValor1[$i]));
                            }
                            $restoValor=$arrayValor1[$i];
                            $restoMonedasVender=0;
                            $i=$i-1;
                            
                        }elseif ($array1[$i]<=$restoMonedasVender) {
                            
                            $restoMonedasVender=$restoMonedasVender-$array1[$i];
                            
                            if ($restoValor>$arrayValor1[$i]) {
                                $beneficioasumar=$beneficioasumar+($array1[$i]*($restoValor-$arrayValor1[$i]));
                                
                            }else {
                                $beneficioarestar=$beneficioarestar+($array1[$i]*($restoValor-$arrayValor1[$i]));
                            }
                            $restoValor=$restoValor;
                
                        }
                    }else{
                        for ($j=$buclecontarray2; $j <count($array2); $j++) {
                            if ($resto>0) {
                    
                                if ($resto>$array2[$j]) {
                                    
                                    $resto=$resto-$array2[$j];
                                    $restoValor=$arrayValor1[$i];
                                    if ($arrayValor2[$j]>$arrayValor1[$i]) {
                                        $beneficioasumar=$beneficioasumar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                        
                                    }else {
                                        $beneficioarestar=$beneficioarestar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                    }
                        
                                }elseif ($resto<=$array2[$j]) {
                                    
                                    $restoMonedasVender=$array2[$j]-$resto;
                                    
                                    if ($arrayValor2[$j]>$arrayValor1[$buclecontarray2]) {
                                        $beneficioasumar=$beneficioasumar+($resto*($arrayValor2[$j]-$arrayValor1[$buclecontarray2]));
                                        
                                    }else {
                                        $beneficioarestar=$beneficioarestar+($resto*($arrayValor2[$j]-$arrayValor1[$buclecontarray2]));
                                    }
                                    $resto=0;
                                    $buclecontarray2=$j+1;
                                    $restoValor=$arrayValor2[$j];
                                    break;
                        
                                }
                            }else {
                                if ($array1[$i]>$array2[$j]) {
                    
                                    $resto=$array1[$i]-$array2[$j];
                                    
                                    if ($arrayValor2[$j]>$arrayValor1[$i]) {
                                        $beneficioasumar=$beneficioasumar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                        
                                    }else {
                                        $beneficioarestar=$beneficioarestar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                    }
                                    $restoValor=$arrayValor1[$i];
                        
                                }elseif ($array1[$i]<=$array2[$j]) {
                        
                                    $restoMonedasVender=$array2[$j]-$array1[$i];
                                    
                                    if ($arrayValor2[$j]>$arrayValor1[$i]) {
                                        $beneficioasumar=$beneficioasumar+($array1[$i]*($arrayValor2[$j]-$arrayValor1[$i]));
                                        
                                    }else {
                                        $beneficioarestar=$beneficioarestar+($array1[$i]*($arrayValor2[$j]-$arrayValor1[$i]));
                                    }
                                    $restoValor=$arrayValor2[$j];
                                    $buclecontarray2=$j+1;
                                    
                                    break;
                        
                                }
                            }
                        }
                    }
                }
                array_push($datosAux, $monedaF);
                array_push($datosAux, $beneficioasumar+$beneficioarestar);
                array_push($datosArray, $datosAux);
            }
            
        }
        return$datosArray;
    }
    /*
        MUESTRA UN RANKING DE USUARIOS SEGUN SUS BENEFICIOS
    */
    static public function ctrRanking(){
        $tabla='usuarios';
        $datosArray= array();
        $hoy = getdate();
        $anyo="%".$hoy["year"]."%";
        $datosUsuarios=ModeloUsuarios::MdlMostrarUsuarios($tabla,null,null);
        foreach ($datosUsuarios as $key => $value) {
            if ($value['rol']=="Usuario") {
                $datosBilletera=ModeloCompraVenta::MdlSelectTodasMonedasCartera($value['n_usuario']);
                $BeneficioTotal=0; 
                $datosAux= array();
                foreach ($datosBilletera as $key => $value) {
                    $monedaF=$value["id_moneda"];
                    $datosSelect = array(
                        "moneda" =>  $value["id_moneda"],
                        "n_usuario" => $value['n_usuario'],
                        "anyo" => $anyo
                    );
                    $datosCompra=ModeloCompraVenta::MdlSelectCompra($datosSelect);
                    $datosVenta=ModeloCompraVenta::MdlSelectVenta($datosSelect);
                    if ($datosVenta!=null) {
                        $arrayValor1=array();
                        $arrayValor2=array();
                        $array1 = array();
                        $array2 = array();
                        foreach ($datosCompra as $key => $value) {
                            array_push($arrayValor1, $value['valor']);
                            array_push($array1, $value['cantidad']);
                        }
                        foreach ($datosVenta as $key => $value) {
                            array_push($arrayValor2, $value['valor']);
                            array_push($array2, $value['cantidad']);
                        }
                        $buclecontarray2=0;
                        $beneficioasumar=0;
                        $beneficioarestar=0;
                        $resto=0;
                        $restoMonedasVender=0;
                        $restoValor=0;
                        for ($i=0; $i <count($array1); $i++) {
                            if ($restoMonedasVender>0) {
                    
                                if ($array1[$i]>$restoMonedasVender) {
                            
                                    $resto=$array1[$i]-$restoMonedasVender;
                                    
                                    if ($restoValor>$arrayValor1[$i]) {
                                        $beneficioasumar=$beneficioasumar+($restoMonedasVender*($restoValor-$arrayValor1[$i]));
                                        
                                        
                                    }else {
                                        $beneficioarestar=$beneficioarestar+($restoMonedasVender*($restoValor-$arrayValor1[$i]));
                                    }
                                    $restoValor=$arrayValor1[$i];
                                    $restoMonedasVender=0;
                                    $i=$i-1;
                                    
                                }elseif ($array1[$i]<=$restoMonedasVender) {
                                    
                                    $restoMonedasVender=$restoMonedasVender-$array1[$i];
                                    
                                    if ($restoValor>$arrayValor1[$i]) {
                                        $beneficioasumar=$beneficioasumar+($array1[$i]*($restoValor-$arrayValor1[$i]));
                                        
                                    }else {
                                        $beneficioarestar=$beneficioarestar+($array1[$i]*($restoValor-$arrayValor1[$i]));
                                    }
                                    $restoValor=$restoValor;
                        
                                }
                            }else{
                                for ($j=$buclecontarray2; $j <count($array2); $j++) {
                                    if ($resto>0) {
                            
                                        if ($resto>$array2[$j]) {
                                            
                                            $resto=$resto-$array2[$j];
                                            $restoValor=$arrayValor1[$i];
                                            if ($arrayValor2[$j]>$arrayValor1[$i]) {
                                                $beneficioasumar=$beneficioasumar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                                
                                            }else {
                                                $beneficioarestar=$beneficioarestar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                            }
                                
                                        }elseif ($resto<=$array2[$j]) {
                                            
                                            $restoMonedasVender=$array2[$j]-$resto;
                                            
                                            if ($arrayValor2[$j]>$arrayValor1[$buclecontarray2]) {
                                                $beneficioasumar=$beneficioasumar+($resto*($arrayValor2[$j]-$arrayValor1[$buclecontarray2]));
                                                
                                            }else {
                                                $beneficioarestar=$beneficioarestar+($resto*($arrayValor2[$j]-$arrayValor1[$buclecontarray2]));
                                            }
                                            $resto=0;
                                            $buclecontarray2=$j+1;
                                            $restoValor=$arrayValor2[$j];
                                            break;
                                
                                        }
                                    }else {
                                        if ($array1[$i]>$array2[$j]) {
                            
                                            $resto=$array1[$i]-$array2[$j];
                                            
                                            if ($arrayValor2[$j]>$arrayValor1[$i]) {
                                                $beneficioasumar=$beneficioasumar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                                
                                            }else {
                                                $beneficioarestar=$beneficioarestar+($array2[$j]*($arrayValor2[$j]-$arrayValor1[$i]));
                                            }
                                            $restoValor=$arrayValor1[$i];
                                
                                        }elseif ($array1[$i]<=$array2[$j]) {
                                
                                            $restoMonedasVender=$array2[$j]-$array1[$i];
                                            
                                            if ($arrayValor2[$j]>$arrayValor1[$i]) {
                                                $beneficioasumar=$beneficioasumar+($array1[$i]*($arrayValor2[$j]-$arrayValor1[$i]));
                                                
                                            }else {
                                                $beneficioarestar=$beneficioarestar+($array1[$i]*($arrayValor2[$j]-$arrayValor1[$i]));
                                            }
                                            $restoValor=$arrayValor2[$j];
                                            $buclecontarray2=$j+1;
                                            
                                            break;
                                
                                        }
                                    }
                                }
                            }
                        }
                        $BeneficioTotal=$BeneficioTotal+($beneficioasumar+$beneficioarestar);
                    }
                    
                }
                array_push($datosAux,$value['n_usuario']);
                array_push($datosAux, $BeneficioTotal);
                array_push($datosArray, $datosAux);
            }
        }
        
        
        return$datosArray;
    }
    /*
        ACTUALIZA LOS BENEFICIOS DE LAS MONEDAS
    */
    static public function ctrActualizarBeneficios(){

        
        if (isset($_POST['EideMoneda'])) {
            if ($_POST["EBeneficioCompra"]==null || $_POST["EBeneficioCompra"]<=0) {
                $_POST["EBeneficioCompra"]=0;
            }
            if ($_POST["EBeneficioVenta"]==null || $_POST["EBeneficioVenta"]<=0) {
                $_POST["EBeneficioVenta"]=0;
            }
            if ($_POST["EBeneficioCompra"]>100) {
                $_POST["EBeneficioCompra"]=100;
            }
            if ($_POST["EBeneficioVenta"]>100) {
                $_POST["EBeneficioVenta"]=100;
            }
            $_POST["EBeneficioCompra"]=round($_POST["EBeneficioCompra"],1);
            $_POST["EBeneficioVenta"]=round($_POST["EBeneficioVenta"],1);
            $respuesta = ModeloMoneda::MdlActivarMonedas("monedas",$_POST["EideMoneda"],$_POST["EBeneficioCompra"],$_POST["EBeneficioVenta"],"1");
            if($respuesta == "ok"){
                echo '<script>
            
                    Swal.fire({
                        icon: "success",
                        title: "Perfecto",
                        text: "¡Acción ejecutada correctamente!",
                        confirmButtonText: "Cerrar"
                    }).then((result)=>{
                        if(result.value){
                            window.location = "beneficios";
                        }
                    })

                    
                    </script>';
            }
        }
    }


}