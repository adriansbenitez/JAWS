<? if (strpos($_SERVER["REQUEST_URI"], "ajax/") != false) {
    $sufijo = "../";
}
include $sufijo.'trozos/session.php';
require_once $sufijo.'clases/Pregunta.php';
require_once $sufijo.'clases/Paginacion.php';
seguridad("admin");
?><ul class="listadmin">
<? $paginas = new Paginacion(Pregunta_BD::contarPreguntasPerdidas());
$listaPreguntas = Pregunta_BD::listarPerdidas($paginas->minimo(), $paginas->getTamanyoPaginas());
$pregunta = new Pregunta(null, null, null, null, null, null, null, null, null);
foreach ($listaPreguntas as $pregunta) {
    ?>
        <li data-color="50" class="colorfondo">
            <p><? echo $pregunta->getCodigo(); ?><br/>
            <? echo $pregunta->getEnunciado(200); ?></p>
            <? if ($pregunta->getActivo()) {
                $icono = "adminblock.png";
            } else {
                $icono = "adminalert.png";
                ?><a href="funciones/controlador.php?accion=borrarPregunta&id_pregunta=<? echo $pregunta->getIdPregunta(); ?>">
                    <img src="css/icos/admindelete.png" title="Borrar"/>
                </a>
            <? } ?>
            <a href="funciones/controlador.php?accion=cambiaEstadoPregunta&id_pregunta=<? echo $pregunta->getIdPregunta(); ?>">
                <img src="css/icos/<? echo $icono; ?>" title="Cambiar estado"/>
            </a>
            <a href="editpregunta.php?id_pregunta=<? echo $pregunta->getIdPregunta(); ?>&id_anyo=0">
                <img src="css/icos/adminedit.png" title="Editar pregunta"/>
            </a>
    <? } ?>
</ul>
<div id="contentPaginacion">
<? $paginas->montarPaginas(""); ?>
</div>