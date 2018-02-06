<?php
include '../trozos/session.php';

$bd = new BD();

$bd->setConsulta("select c.id_curso, c.nombre from cursos c order by c.nombre");
$bd->ejecutar();
?><ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden">
<? while($it = $bd->resultado()){ ?>
    <li class="seccionli">
        <img src="css/icos/icoplus.png" rel="<? echo $it["id_curso"]; ?>" height="23"
             border="0" class="show" align="absmiddle" onclick="cargaTemas(this)" estado="0"/>
        <? echo $it["nombre"]; ?>
    </li>
    <div id="dentroCurso<? echo $it["id_curso"] ?>"></div>
<? } ?>
</ul>