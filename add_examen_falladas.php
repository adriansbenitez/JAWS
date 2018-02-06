<?
include 'trozos/session.php';
require_once 'clases/Examen.php';
require_once 'clases/Curso.php';
require_once 'clases/Grupo.php';
seguridad("usuario_falladas");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <link type="text/css" rel="stylesheet" href="css/examenes.css"/>
        <script type="text/javascript" src="js/examenes_fallados.js?ver=1.0"></script>
        <title>Plataforma de Formacion Inspiracle</title>
        <link type="text/css" rel="stylesheet" href="css/calendario.css"/>
    </head>
    <body>
        <div id="tapa">
        </div>
        <div id="tapa2">
            <div id=error>
                <b>Su examen se está elaborando, esta operación puede tardar hasta un minuto</b>
                <div class="clear"></div>
                <div style="height: 30px; margin: 10px;"><img alt="cargando" src="images/progress_bar_barra.gif" /></div>
            </div>
        </div>
<? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Crear Exámenes <img src="css/icos/icoexamenes.png" /></h1>
                <a href="listexamenes.php" class="botonoscuro">&lt;&lt; Atras</a>
                <hr />
                <div class="clear"></div>
                <form action="funciones/controlador.php" id="frmcrearexamen" class="frmadmin" method="post">
                    <input type="hidden" name="accion" value="crearExamenFalladas"/>
                    <div id="mensajero"></div>
                    <input type="hidden" name="cursos" id="arrCursos" value=""/>
                    <input type="hidden" name="temas" id="arrTemas" value=""/>
                    <input type="hidden" name="unidades" id="arrUnidades" value=""/>
                    <input type="hidden" name="anyos" id="arrAnyos" value=""/>
                    <input type="hidden" name="listaAlumnos" id="listaAlumnos"/>

                    <label>Nombre de examen</label><input name="titulo" type="text"/>
                    <div class="clear"></div>
                    <label>Tiempo Máximo<br/>(Minutos 0 sin límite)</label>
                    <input type="text" name="tiempo" value="0"/>

                    <div class="clear"></div>
                    <label>Número de preguntas</label><input class="auto" name="numpreguntas" type="text" title="vacio" />
                    <div class="clear"></div>

                    <hr/>
                    <input type="button" value="Crear Examen" class="botonEnviar"/>
                    <div class="clear"></div>
                    <? $listaCursos = Curso_BD::paraExamenFalladas($_SESSION["usuario"]);
                    $curso = new Curso(null, null, null);
                    ?>
                    <ul class="listadmin categoriasli">
                        <? foreach ($listaCursos as $curso) { ?>
                            <li>
                                <img src="css/icos/icoplus.png" height="23" border="0" class="show lista-cursos" align="absmiddle"
                                     onclick="expandeCursos(this)" rel="<?= $curso->getIdCurso() ?>"/>
                                <span class="contador">(Nº Preguntas:<?= $curso->getTotalPreguntas() ?>)</span>
                                <? echo $curso->getNombre(); ?>
                                <input type="checkbox" id="Curso<?= $curso->getIdCurso() ?>"
                                       value="<?= $curso->getIdCurso() ?>" class="check-curso" onclick="cambiaCurso(this)"
                                       preg="<?= $curso->getTotalPreguntas() ?>"/>
                            </li>
                            <div id="dentroCurso<?= $curso->getIdCurso() ?>"></div>
                        <? } ?>
                    </ul>
                    <div class="clear"></div>
                </form>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
