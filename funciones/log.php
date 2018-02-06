<?php

$sufijo = "";
if (strpos($_SERVER["REQUEST_URI"], "clases/") != false) {
    $sufijo = "../";
}
if (strpos($_SERVER["REQUEST_URI"], "funciones/") != false) {
    $sufijo = "../";
}

if (strpos($_SERVER["REQUEST_URI"], "ajax/") != false) {
    $sufijo = "../";
}

require_once $sufijo . 'clases/Correo.php';

function escribeLog($texto, $critico = false) {

    date_default_timezone_set('Europe/Madrid');

    $archivo = fopen(dirname(__FILE__). "/logs/" . date("Ymd") . ".log", "a");

    $fecha = date("Y/m/d - H:i:s");
    $usuario = "Anónimo";

    if (isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"]->getEmail();
    }

    $texto = $fecha . " -\t" . $_SERVER["REMOTE_ADDR"] . " -\t" . $usuario . " -\t" . $texto;
    if ($critico) {
        $texto.= "\n" . print_r(debug_backtrace(), TRUE);
    }

    
    fwrite($archivo, $texto . "\n");

    if ($critico) {
        $correo = new Correo();
        $correo->agregarDestinatarios("doc_nachondo@hotmail.com");
        $correo->setAsunto("ALERTA");
        $correo->setPlantilla("errores.html");
        $correo->agregarRemplazo("texto", str_replace("\n", "<br/>", $texto));
        $correo->enviar(true);
    }
}

function primeraLinea() {
    $direccion = $_SERVER["REQUEST_URI"];
    $contenido = null;
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (strpos($_SERVER["REQUEST_URI"], "?") != false) {
            $direccion = substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], "?"));
        }
        $contenido = $_SERVER["QUERY_STRING"];
    } else {
        foreach ($_POST as $clave => $valor) {
            if (isset($contenido)) {
                $contenido .= "&" . $clave . "=" . $valor;
            } else {
                $contenido = "?" . $clave . "=" . $valor;
            }
        }
    }
    
    if(!empty($contenido)){
        escribeLog("Entrando en " . $direccion . " -> " . $contenido . "[" . $_SERVER["REQUEST_METHOD"] . "]");
    }else{
        escribeLog("Entrando en " . $direccion . "[" . $_SERVER["REQUEST_METHOD"] . "]");
    }
    
}

function escribeLogTest($texto){
    
    date_default_timezone_set('Europe/Madrid');
    
    $archivo = fopen(__DIR__."/logs/test.log", "a");
    
    $fecha = date("Y/m/d - H:i:s");
    
    $texto = $fecha." -\t".$texto;
    
    fwrite($archivo, $texto."\n");
}