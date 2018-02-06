<?php

include '../trozos/session.php';
seguridad("admin");
require_once '../clases/Anyo.php';

$listaAnyos = Anyo_BD::listaIdUnidad($_GET["id_unidad"]);
$anyo = new Anyo(null, null, null, null);
?><ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden"><?
foreach ($listaAnyos as $anyo){?>
    <li class="seccionli">
        <span class="pbar" style=" width:<?=$anyo->peso()?>%"><?=$anyo->peso()?>%</span>
        <span class="contador">(<?=$anyo->getTotalPreguntas()?>)</span>
        <?=$anyo->getNombre()?>
        <a href="funciones/controlador.php?accion=borrarCategoria&id_anyo=<?=$anyo->getIdAnyo()?>"
            onclick="return confirm('Confirma la eliminación de la entidad');">
            <img src="css/icos/delete.png" />
        </a>
        <a href="listpreguntas.php?id_anyo=<?=$anyo->getIdAnyo()?>"><img src="css/icos/adminquestion.png" /></a>
        <a href="editcurso.php?id_anyo=<?=$anyo->getIdAnyo()?>"><img src="css/icos/adminedit.png" /></a>
    </li>
<? } ?>
</ul>