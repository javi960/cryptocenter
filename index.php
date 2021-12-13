<?php

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/monedas.controlador.php";
require_once "controladores/fondos.controlador.php";
require_once "controladores/CompraVenta.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/monedas.modelo.php";
require_once "modelos/fondos.modelo.php";
require_once "modelos/CompraVenta.modelo.php";







$plantilla = new ControladorPlantilla();

$plantilla -> ctrPlantilla();