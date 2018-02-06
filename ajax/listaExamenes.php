<?php
if (strpos($_SERVER["REQUEST_URI"], "ajax/") != false) {
    $sufijo = "../";
}
include $sufijo . 'trozos/session.php';
require_once $sufijo . 'clases/Examen.php';
require_once $sufijo . 'clases/Paginacion.php';
seguridad("usuario"); ?>
<ul class="listadmin">
    <? $filtro = null;
    if(isset($_POST["filtro"])){
        $filtro = $_POST["filtro"];
    }
    
    $periodo = 0;
    if(isset($_POST["periodo"])){
        $periodo = $_POST["periodo"];
    }
    
    $paginacion = new Paginacion(Examen_BD::contarExamenes($_SESSION["usuario"]->getNivel(), $_SESSION["usuario"]->getIdUsuario(), $filtro, $periodo));
    $paginacion->setTamanyoPaginas(20);
    $listaExamenes = Examen_BD::listaExamenes($_SESSION["usuario"]->getNivel(), $_SESSION["usuario"]->getIdUsuario(), $filtro, $paginacion->minimo(), $paginacion->getTamanyoPaginas(), $periodo);
    $examen = new Examen(null, null, null, null, null);
    foreach ($listaExamenes as $examen) { ?>
            <li data-color="<? $porExamen = explode(",", $examen->porcentaje());
            echo $porExamen[0];
            ?>" class="colorfondo">
                <span class="porcien"><?=$porExamen[0]?><span>,<?=$porExamen[1]?>%</span></span>
                <p class="clearfix"><?=$examen->getTitulo(200)?></p>
        <? if ($_SESSION["usuario"]->getNivel() == 1) {
            if ($examen->getActivo()) { ?>
                        <a href="funciones/controlador.php?accion=cambiaEstadoExamen&id_examen=<?=$examen->getIdExamen()?>">
                            <img src="css/icos/adminblock.png" />
                        </a><? } else {
                ?>
                        <a href="funciones/controlador.php?accion=cambiaEstadoExamen&id_examen=<?=$examen->getIdExamen()?>">
                            <img src="css/icos/adminalert.png" />
                        </a>
                        <a href="funciones/controlador.php?accion=borrarExamen&id_examen=<?=$examen->getIdExamen()?>">
                            <img src="css/icos/admindelete.png" />
                        </a>
                <? } ?><a href="editexamen.php?id_examen=<?=$examen->getIdExamen()?>">
                        <img src="css/icos/adminedit.png" />
                    </a>
                    <a href="analizadorexamen.php?id_examen=<?=$examen->getIdExamen()?>">
                        <img style="margin-right: 6px;" src="css/icos/adminstats.png">
                    </a>
                    <a href="rendirexamen.php?id_examen=<?=$examen->getIdExamen()?>">
                        <img src="css/icos/admindetalles.png" />
                    </a>
                    <? $listaNombres = $examen->getListaNombres();
                    
                        if (count($listaNombres) > 0) {
                            ?><div>Dirigido a: <?
                            $c = 0;
                            foreach ($listaNombres as $nombre) {
                                $c++;
                                if ($c > 1) {
                                    echo "; ";
                                }
                                echo $nombre;
                            }
                            ?></div><?
                        }
                        if($examen->getNombresGrupos() != null){ ?>
                            <div>Grupos: <? $c = 0;
                            foreach ($examen->getNombresGrupos() as $nombre) {
                                $c++;
                                if ($c > 1) {
                                    echo ", ";
                                }
                                echo $nombre;
                            }
                        ?></div>
                    <? } ?>
                <? } else { 
                    if($examen->getIdAutor() == $_SESSION["usuario"]->getIdUsuario()){ ?>
                <a href="funciones/controlador.php?accion=borrarExamen&id_examen=<?=$examen->getIdExamen()?>" onclick="return confirm('Se eliminará el examen <?=$examen->getTitulo()?>\n¿Desea continuar?')">
                            <img src="css/icos/admindelete.png" />
                        </a>
                    <? } ?>
                    <a href="rendirexamen.php?id_examen=<?=$examen->getIdExamen()?>">
                        <img src="css/icos/admindetalles.png" />
                    </a>
            <? } ?>
            </li>
    <? } ?>
</ul>
<div id="contentPaginacion">
    <? $paginacion->montarPaginas(""); ?>
</div>