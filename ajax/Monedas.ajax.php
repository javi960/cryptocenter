<?php

require_once "../controladores/monedas.controlador.php";
require_once "../modelos/monedas.modelo.php";
require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";
require_once "../modelos/CompraVenta.modelo.php";

class AjaxVMonedas{

    public $Nmoneda;

    
    public function ajaxDesactivarMoneda(){
        $tabla = "monedas";
        $moneda= $this->Nmoneda;
        $respuesta = ModeloMoneda::MdlActivarMonedas($tabla,$moneda,0,0,"0");
        echo json_encode($respuesta);
    }
}


/*
    DESACTIVAR MONEDA
*/

if (isset($_POST["Nmoneda"])) {
    $desactivar = new AjaxVMonedas();
    $desactivar -> Nmoneda = $_POST["Nmoneda"];
    $desactivar-> ajaxDesactivarMoneda();
}
/*
    SABER CANTIDAD DE MONEDAS
*/
if (isset($_POST["VNmoneda"])) {
    $datos= array(
        "n_usuario" =>$_POST["usuario"],
        "id_moneda" => $_POST["VNmoneda"]
    );
    $respuesta = ModeloCompraVenta::MdlSelectMonedasCartera($datos);
    echo json_encode($respuesta);
}

if (isset($_POST["idVmoneda"])) {
    $partes = explode("-",$_POST["idVmoneda"]);
    $datosFondosUsuario=ModeloUsuarios::MdlMostrarFUsuario($partes[1]);
    $hoy = getdate();
    $fecha=$hoy["year"]."-".($hoy["mon"]-1)."-01T00:00:00";
    $urlDatos="https://rest.coinapi.io/v1/exchangerate/".$partes[0]."/".$datosFondosUsuario['moneda_fondo']."/history?period_id=10DAY&time_start=".$fecha;
    $respuesta = array();
    /**/
    if (file_exists("../vistas/json/".$partes[0].".json")) {
        if (date ("d", filemtime("../vistas/json/".$partes[0].".json"))!=date("d")) {
            $json = json_encode(ControladorMonedas::ctrMostrarDAPI($urlDatos));
            $bytes = file_put_contents("../vistas/json/".$partes[0].".json", $json);
            $data = file_get_contents("../vistas/json/".$partes[0].".json");
            $decodificar = json_decode($data);
            $res=json_decode($decodificar);
        }else {
            
            $data = file_get_contents("../vistas/json/".$partes[0].".json");
            $decodificar = json_decode($data);
            $res=json_decode($decodificar);
        }
    } else {
        $json = json_encode(file_get_contents("https://rest.coinapi.io/v1/assets/icons/32?apikey=255AEDF5-A559-4623-9F4C-971E4B506D89&invert=true&output_format=json"),true);
        $bytes = file_put_contents("../vistas/json/".$partes[0].".json", $json);
        $data = file_get_contents("../vistas/json/".$partes[0].".json");
        $decodificar = json_decode($data);
        $res=json_decode($decodificar);
    }
    foreach ($res as $key => $value) {
        array_push($respuesta, $value["rate_open"]);
        echo '<script>console.log('.$value["rate_open"].')</script>';
    }
    echo json_encode($res);
}
/*
    BENEFICIOS DE LA MONEDA
*/
if (isset($_POST["Identificadormoneda"])) {
    
    $respuesta = ModeloMoneda::MdlMostrarMoneda("monedas","id_moneda",$_POST["Identificadormoneda"]);
    echo json_encode($respuesta);
}
/*
    ELIMINAR MONEDA
*/
if (isset($_POST["Dmoneda"])) {
    $partes=explode("-", $_POST["Dmoneda"]);
    $datos= array(
        "n_usuario" =>$partes[1],
        "id_moneda" => $partes[0]
    );
    $tabla='monedas_cartera';
    $eliminarMonedasCartera=ModeloMoneda::MdlEliminarSMoneda($tabla,$datos);
    if ($eliminarMonedasCartera=='ok') {
        echo json_encode($eliminarMonedasCartera);
    }

}
/*
    GRAFICO MONEDA
*/
if (isset($_POST["datos"])) {
    $partes = explode("-",$_POST["datos"]);
    $moneda=ModeloUsuarios::MdlMostrarFUsuario($partes[1]);
    $hoy = getdate();
    $diaMes;
    $monedaBusqueda;
    if ($hoy["mday"]<10) {
        $diaMes="0".$hoy["mday"];
    }else{
        $diaMes=$hoy["mday"];
    }
    if (isset($moneda['moneda_fondo'])) {
        $monedaBusqueda=$moneda['moneda_fondo'];
    }else {
        $monedaBusqueda="USD";
    }
    $fecha1=$hoy["year"]."-".($hoy["mon"]-1)."-".$diaMes."T00:00:00";
    $fecha2=$hoy["year"]."-".$hoy["mon"]."-".$diaMes."T00:00:00";
    $url="https://rest.coinapi.io/v1/exchangerate/".$partes[0]."/".$monedaBusqueda."/history?period_id=1DAY&time_start=".$fecha1."&time_end=".$fecha2;
    $respuesta = ControladorMonedas::ctrMostrarDAPI($url);
    echo json_encode($respuesta);
}
