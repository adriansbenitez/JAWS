<?php

include '../trozos/session.php';

$bd = new BD();

$idUsuario = $_GET["id_usuario"];
$idExamen = $_GET["id_examen"];

$sqlGraf4 = "select * from v_resultados_completo where id_usuario = ? and id_examen = ?";

$bd->setConsulta($sqlGraf4);

$parametros = array($idUsuario, $idExamen);

$bd->ejecutar($parametros);

$listaGrafica4 = array();

$notas = "";
$fechas = "";

$cont2 = 0;

while ($it = $bd->resultado()) {

    $cont2++;
    if ($it["puntos"] == 0) {
        $notas.= ",0";
    } else {
        $notas.= "," . number_format(((($it["acertadas"] * 3) - $it["fallidas"]) * 100) / $it["puntos"], 2, ".", "");
    }
    $fechaTemp = explode("-", substr($it["fecha"], 0, 10));
    $fechas.=",'" . $fechaTemp[2] . "/" . $fechaTemp[1] . "/" . $fechaTemp[0] . "'";
}

if ($cont2 > 0) {
    $notas = substr($notas, 1);
    $fechas = substr($fechas, 1);
}

echo "var resp={notas:[$notas],fechas:[$fechas]}";