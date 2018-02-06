<?php

include '../trozos/session.php';

$bd = new BD();

$bd->setConsulta("select a.nombre, a.id_anyo, concat(c.nombre,' / ',t.nombre,' / ',u.nombre,' / ',a.nombre) completo
 from anyos a inner join unidades u on u.id_unidad = a.id_unidad
 inner join temas t on t.id_tema = u.id_tema
 inner join cursos c on c.id_curso = t.id_curso
 where a.id_unidad = ? order by nombre");
$bd->ejecutar($_GET["id_unidad"]);
?><ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden">
<? while($it = $bd->resultado()){
?><li class="seccionli">
    <? echo $it["nombre"]; ?>
        <input type="button" value="seleccionar" class="asociar" rel="<?=$it["id_anyo"]?>"
            rel2="<?=$it["completo"]?>" onclick="agregaAnyo(this)"/>
    </li>
<? } ?>
</ul>