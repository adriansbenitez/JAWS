<?php
include '../trozos/session.php';

$bd = new BD();

$bd->setConsulta("select u.id_unidad, u.nombre, concat(c.nombre,' / ', t.nombre,' / ',u.nombre) completo
 from unidades u inner join temas t on t.id_tema = u.id_tema
 inner join cursos c on c.id_curso = t.id_curso
 where u.id_tema = ? and u.activo = 1
 order by nombre");
$bd->ejecutar($_GET["id_tema"]);
if ($_GET["virtual"]) {
    ?><ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden">
    <? while ($it = $bd->resultado()) {
        ?><li class="seccionli">
            <?= $it["nombre"]; ?>
            <input type="button" value="seleccionar" class="asociar" rel="<?= $it["id_unidad"]; ?>"
            rel2="<?= $it["completo"]; ?>" onclick="agregaUnidadVirtual(this)"/>
        </li>
    <? } ?>
    </ul><?    
} else {
    ?><ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden">
    <? while ($it = $bd->resultado()) {
        ?><li class="seccionli">
                <img src="css/icos/icoplus.png" rel="<?= $it["id_unidad"]; ?>" onclick="cargaAnyos(this)"
                     height="23" border="0" class="show" align="absmiddle" estado="0" />
                     <?= $it["nombre"]; ?>
            </li>
            <div id="dentroUnidad<?= $it["id_unidad"]; ?>"></div>
        <? } ?>
    </ul><?
}
