<?php

error_reporting(E_ALL ^ E_WARNING);

$sufijo = "";

if (strpos($_SERVER["REQUEST_URI"], "ajax/") != false) {
    $sufijo = "../";
}

require_once $sufijo.'clases/Usuario.php';
require_once $sufijo.'clases/Examen.php';
require_once $sufijo.'clases/Provincia.php';
require_once $sufijo.'clases/Fecha.php';
require_once $sufijo.'clases/Periodo.php';
require_once $sufijo.'funciones/log.php';
require_once $sufijo.'funciones/seguridad.php';


if(!isset($_SESSION)){
    session_start(); 
}

primeraLinea();