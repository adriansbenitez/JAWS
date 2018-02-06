<? include 'trozos/session.php';
require_once 'clases/Pregunta.php';
require_once 'clases/Categoria.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/recargasAdmin.js?ver=1.1"></script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <? $categoria = new Categoria(null, null, null, null, null);
                if(isset($_GET["id_anyo"])){
                    $categoria = Categoria_BD::porId($_GET["id_anyo"], 4);
                } ?><h1>Preguntas de <?=$categoria->getNombreCompleto()?></h1>
                <a href="listcursos.php" class="botonoscuro">&lt;&lt; Atrás</a>
                <a href="addpregunta.php?id_anyo=<?=$categoria->getId()?>" class="botonoscuro">+ Añadir</a>
                <hr />
                <div class="clear"></div>
                <ul class="listadmin">
                    <? $lista = Pregunta_BD::porAnyo($categoria->getId());
                    $pregunta = new Pregunta(null, null, null, null, null, null, null, null, null);
                    foreach ($lista as $pregunta){ ?>
                    <li data-color="<? echo $pregunta->porcentaje(); ?>" class="colorfondo">
                        <span class="porcien"><? $por = explode(".", $pregunta->porcentaje()); echo $por[0]; ?><span>,<? echo $por[1]; ?>%</span></span>
                        <p>
                        <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                        <b><?=$pregunta->getCodigo()?></b><br/>
                        <? } ?>
                        <?=$pregunta->getEnunciado(200)?></p>
                        <? if($pregunta->getActivo()){
                            $icono="adminblock.png";
                        }else{
                            $icono="adminalert.png";
                            ?><a href="funciones/controlador.php?accion=borrarPregunta&id_pregunta=<?=$pregunta->getIdPregunta()?>&id_anyo=<?=$categoria->getId()?>">
                                <img src="css/icos/admindelete.png" title="Borrar"/>
                        </a>
                        <? } ?><a href="funciones/controlador.php?accion=cambiaEstadoPregunta&id_pregunta=<?=$pregunta->getIdPregunta()?>&id_anyo=<?=$categoria->getId()?>">
                            <img src="css/icos/<?=$icono?>" title="Cambiar estado"/>
                        </a>

                        <a class="aministats">
                            <img src="css/icos/adminstats.png" />
                            <div class="ministats">
                                <img src="css/icos/correcionok.png" /><?=$pregunta->getCorrectas()?>
                                <img src="css/icos/correcionko.png" /><?=$pregunta->getIncorrectas()?>
                                <img src="css/icos/correcionnsnc.png"  /><?=$pregunta->getBlancas()?>
                            </div>
                        </a>
                        <a href="editpregunta.php?id_pregunta=<?=$pregunta->getIdPregunta()?>&id_anyo=<?=$categoria->getId()?>">
                            <img src="css/icos/adminedit.png" title="Editar pregunta"/>
                        </a>
                    </li>
                    <? } ?>
                </ul>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>