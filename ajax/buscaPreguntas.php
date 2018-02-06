<? include '../trozos/session.php';
include '../clases/Pregunta.php';

seguridad("admin");
if (!empty($_POST["campo1"]) || !empty($_POST["campo2"]) || !empty($_POST["campo3"])) {
    $pregunta = new Pregunta(null, null, null, null, null, null, null, null, null);
    $listaPreguntas = Pregunta_BD::resultadoBuscador($_POST["campo1"], $_POST["campo2"], $_POST["campo3"], $_POST["preguntas"]);?>
    <ul class="listadmin"><? 
    foreach ($listaPreguntas as $pregunta) { ?>
        <li data-color="50" class="colorfondo">
            <p><?=$pregunta->getCodigo(); ?><br/>
            <?=$pregunta->getEnunciado(200); ?></p>
            <? if ($pregunta->getActivo()) {
                $icono = "adminblock.png";
            } else {
                $icono = "adminalert.png";
                ?><a href="funciones/controlador.php?accion=borrarPregunta&id_pregunta=<?= $pregunta->getIdPregunta(); ?>&id_anyo=-1">
                    <img src="css/icos/admindelete.png" title="Borrar"/>
                </a>
            <? } ?>
                <a href="funciones/controlador.php?accion=cambiaEstadoPregunta&id_pregunta=<?= $pregunta->getIdPregunta(); ?>&id_anyo=-1">
                    <img src="css/icos/<? echo $icono; ?>" title="Cambiar estado"/>
                </a>
                <a href="editpregunta.php?id_pregunta=<?= $pregunta->getIdPregunta(); ?>&id_anyo=-1">
                    <img src="css/icos/adminedit.png" />
                </a>
                <div class="clear"></div>
                <? foreach ($pregunta->getListaAnyos() as $any){
                    $anyo = explode("@", $any);?>
                    <div class="preguntaAgregable" rel1="<?=$pregunta->getCodigo();?>"
                         rel2="<?=$pregunta->getEnunciado(200); ?>"
                         rel3="<?=$pregunta->getIdPregunta(); ?>"
                         rel4="<?=$anyo[0]?>"
                         rel5="<?=$anyo[1]?>"
                         estado="0" onClick="agrega($(this))">
                        <?=$anyo[1]?>
                    </div>
                <? } ?>
                
            </li>
        <? } ?></ul><? } else {
        ?><h2>Debes marcar al menos un criterio</h2><?
}