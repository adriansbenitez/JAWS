<?php
include '../trozos/session.php';

$bd = new BD();

$bd->setConsulta("select t.id_tema, t.nombre from temas t where t.id_curso = ? and activo = 1 order by nombre");
$bd->ejecutar($_GET["id_curso"]);
?><ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden">
<? while($it = $bd->resultado()){
?><li class="seccionli">
    <img src="css/icos/icoplus.png" rel="<? echo $it["id_tema"] ?>" onclick="cargaUnidades(this)"
        height="23" border="0" class="show" align="absmiddle" estado="0" />
        <? echo $it["nombre"]; ?>
</li>
<div id="dentroTema<? echo $it["id_tema"];?>"></div>
<? } ?>
</ul>