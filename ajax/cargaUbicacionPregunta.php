<?php

include '../trozos/session.php';

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]->getNivel() != 1) {
    die();
}

$bd = new BD();

$bd->setConsulta("select distinct titulo from examenes e inner join examenes_preguntas ep on ep.id_examen = e.id_examen where ep.id_pregunta = ? order by titulo");

$bd->ejecutar($_REQUEST["id_pregunta"]);

while($it = $bd->resultado()){
    ?><div><?=$it["titulo"]?></div><?
}