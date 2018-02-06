<?
include 'trozos/session.php';
require_once 'clases/Pregunta.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/recargaTemario.js?ver=1.1"></script>
        <style type="text/css">
            #EqnEditor { text-align:left;}
            #editorEcuacion{float: right;}
            #contiene-temario, #contiene-temario_v{float: right; clear: both;}
            .temario{}
            .ubicaTema{border: 1px solid #1F1F1F; border-radius: 5px 5px 5px 5px; padding: 3px 10px; width: 600px; float:left; margin-bottom: 10px;}
            .modifica, .borra{float:left; transition:all 0.5s; -moz-transition:all 0.5s; -ms-transition:all 0.5s; -o-transition:all 0.5s; -webkit-transition:all 0.5s; margin-left: 5px; cursor: pointer;}
            .modifica:hover, .borra:hover{transform:scale(1.5,1.5); -moz-transform:scale(1.5,1.5); -ms-transform:scale(1.5,1.5); -o-transform:scale(1.5,1.5); -webkit-transform:scale(1.5,1.5);}
            .asociar{background: #0f0f0f !important; color: #ffffff !important; padding: 5px 10px !important; margin: 10px !important;}
            .muestraTemas{float:left; transition:all 0.5s; -moz-transition:all 0.5s; -ms-transition:all 0.5s; -o-transition:all 0.5s; -webkit-transition:all 0.5s; display: none; opacity: 0.0;}
            .listadmin li{cursor: default;}
            .listadmin.categoriasli li .show{cursor: pointer;}
#ubicada{width: 30%;background: #000;color: #fff;border-radius: 10px;padding: 5px 10px;}
        </style>
        <script type="text/javascript">
            function expandir(){
                $.post("ajax/cargaUbicacionPregunta.php", {"id_pregunta":<?=$_GET["id_pregunta"]?>}, function(datos){
                    $('#ubicada').html(datos);
                });
            }
        </script>
    </head>
    <body>
        <?
        include 'trozos/encabezado.php';
        $pregunta = new Pregunta(null, null, null, null, null, null, null, null, null);
        $pregunta = Pregunta_BD::porIdCompleta($_GET["id_pregunta"]);
        ?><div id="content">
            <div class="contplataforma">
                <h1>Editar pregunta<img src="css/icos/icobloques.png" /></h1>
                <? if ($_GET["id_anyo"] == 0) { ?>
                    <a href="listpreguntasPerdidas.php" class="botongrisoscuro">&lt;&lt; Atras</a>
                <? } else if ($_GET["id_anyo"] == -1) { ?>
                    <a href="addexamenPersonalizado.php" class="botongrisoscuro">&lt;&lt; Atras</a>
                <? } else { ?>
                    <a href="listpreguntas.php?id_anyo=<?=$_GET["id_anyo"]?>" class="botonoscuro">&lt;&lt; Atras</a>
                <? } ?>
                <div class="ministats floatright">
                    <img src="css/icos/correcionok.png" /><?=$pregunta->getCorrectas()?>
                    <img src="css/icos/correcionko.png" /><?=$pregunta->getIncorrectas()?>
                    <img src="css/icos/correcionnsnc.png"  /><?=$pregunta->getBlancas()?>
                </div>
                <br /><br />
                <div class="clear"></div>
                <form action="funciones/controlador.php" id="frmcrearpregunta" class="frmadmin" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_pregunta" value="<?=$pregunta->getIdPregunta()?>"/>
                    <input type="hidden" name="id_anyo" value="<?=$_GET["id_anyo"]?>"/>
                    <input type="hidden" name="accion" value="modificaPregunta"/>
                    <? $lista = "";
                    foreach ($pregunta->getListaUnidades() as $unidad){
                        if($lista != ""){
                            $lista.=",";
                        }
                        $lista.=$unidad;
                    } ?>
                    <input type="hidden" name="id_unidad" value="<?=$lista?>"/>
                    <label>Codigo</label>
                    <input name="codigo" type="text" class="requerido" value="<?=$pregunta->getCodigo()?>"/>
                    <div class="clear"></div>
                    <label>Enunciado</label>
                    <textarea class="ckeditor" name="enunciado" title="vacio" class="requerido"><?=$pregunta->getEnunciado()?></textarea>
                    <div class="clear"></div>
                    <label>Imagen del enunciado</label>
                    <? if ($pregunta->getImagenEnunciado() != null) { ?>
                        <img src="upload/pregunta/<?=$pregunta->getImagenEnunciado()?>" />
                        <a href="funciones/controlador.php?accion=eliminafoto&id_pregunta=<?=$pregunta->getIdPregunta()
                            ?>&id_anyo=<?=$_GET["id_anyo"]?>&imagen=<?=$pregunta->getImagenEnunciado()?>&campo=1">
                            <img src="css/icos/admindelete.png" />
                        </a>
                    <? } else { ?>
                        <input name="imagen_enunciado" type="file" value=""/>
                    <? } ?>
                    <div class="clear"></div>
                    <label>Anotación</label>
                    <textarea  class="ckeditor" name="anotacion" placeholder="En este apartado se pueden colocar anotaciones o ayudas para el alumno"
                        ><?=$pregunta->getAnotacion()?></textarea>
                    <div class="clear"></div>
                    <label>Imagen Anotación</label>
                    <? if ($pregunta->getImagenAclaracion() != null) { ?>
                        <img src="upload/pregunta/<?=$pregunta->getImagenAclaracion()?>" />
                        <a href="funciones/controlador.php?accion=eliminafoto&id_pregunta=<?=$pregunta->getIdPregunta()
                            ?>&id_anyo=<?=$_GET["id_anyo"] ?>&imagen=<?=$pregunta->getImagenAclaracion()?>&campo=2">
                            <img src="css/icos/admindelete.png" />
                        </a>
                    <? } else { ?>
                        <input name="imagen_anotacion" type="file" value=""/>
                    <? } ?>
                    <div class="clear"></div>

                    <label>Explicación</label>
                    <textarea class="ckeditor"  placeholder="Explicación acerca de porque la respuesta no es correcta"  name="explicacion"
                        ><?=$pregunta->getExplicacion()?></textarea>
                    <div class="clear"></div>
                    <label>Imagen Explicación</label>
                    <? if ($pregunta->getImagenExplicacion() != null) { ?>
                        <img src="upload/pregunta/<?=$pregunta->getImagenExplicacion()?>" /></a>
                        <a href="funciones/controlador.php?accion=eliminafoto&id_pregunta=<?=$pregunta->getIdPregunta()
                    ?>&id_anyo=<?=$_GET["id_anyo"]?>&imagen=<?=$pregunta->getImagenExplicacion()?>&campo=3">
                            <img src="css/icos/admindelete.png" />
                        </a>
                    <? } else { ?>
                        <input name="imagen_explicacion" type="file" value=""/>
                    <? } ?>
                    <div class="clear"></div>
                    <hr />
                    <label>Video</label>
                    <input name="video" type="text" value="<?=$pregunta->getUrlVideo()?>"/>
                    <div class="clear"></div>
                    <hr />
                    <label>Temario donde se hubica</label>
                    <div id="contiene-temario">
                        <?
                        $t = 0;
                        if ($_GET["id_anyo"] != "0") {
                            $anyo = new Anyo(null, null, null, null);
                            foreach ($pregunta->getListaAnyos() as $anyo) {
                                $t++;
                                ?><div class="temario" id="tem_<?=$t?>">
                                    <div class="ubicaTema"><?=$anyo->getNombreTema()?> / 
                                    <? echo $anyo->getNombreUnidad() ?> - <?=$anyo->getNombre()?>
                                        <input type="hidden" name="anyo_<?=$t?>" value="<?=$anyo->getIdAnyo()?>"/>
                                    </div>
                                    <div class="borra" rel="<?=$t?>" onclick="borrarTema(this)">
                                        <img src="css/icos/admindelete.png" />
                                    </div>
                                </div><?
                            }
                        } else {
                            ?>SIN ASOCIAR<? } ?>
                    </div>
                    <input type="hidden" name="ultimoTemario" value="<?=$t?>" id="ultimoTemario" />
                    <div class="clear"></div>
                    <input type="button" class="asociar" value="asociar al temario" id="btn-asociar"/>
                    <div id="muestraTemas">
                    </div>
                    <hr />
                    <div class="clear"></div>
                    <label>Temario virtual</label>
                    <div id="contiene-temario_v">
                        <? if(count($pregunta->getListaUnidades())>0){
                            $bd2 = new BD();
                            $bd2->setConsulta("select concat(c.nombre,'/', t.nombre,'/',u.nombre) completo, id_unidad from unidades u inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso where u.id_unidad = ?");
                            foreach ($pregunta->getListaUnidades() as $uni){    
                                $bd2->ejecutar($uni);
                                if($it = $bd2->resultado()) { ?>
                                <div class="temario" rel="<?=$it["id_unidad"]?>">
                                    <div class="ubicaTema"><?=$it["completo"]?></div>
                                    <div onclick="borrarUnidadVirtual(<?=$it["id_unidad"]?>)" class="borra">
                                        <img src="css/icos/admindelete.png"/>
                                    </div>
                                </div>
                                <? }
                            }
                        } ?>
                    </div>

                    <div class="clear"></div>
                    <input type="button" class="asociar" value="asociar virtualmente" id="btn-asociar_v"/>
                    <div id="muestraTemas_v">
                    </div>
                    <hr />

                    <div id="contrespuestasform">
                        <?
                        $i = 0;
                        $respuesta = new Respuesta(null, null, null, null, null, null);
                        foreach ($pregunta->getListaRespuestas() as $respuesta) {
                            $i++;
                            if ($respuesta->getCorrecta()) {
                                $cheked = 'checked="checked"';
                            } else {
                                $cheked = "";
                            }
                            ?><label><?
                            if ($respuesta->getActivo()) {
                                $icono = "admindelete.png";
                            } else {
                                $icono = "adminalert.png";
                            } ?>
                                <a href="funciones/controlador.php?accion=eliminaRespuesta&id_respuesta=<?=$respuesta->getIdRespuesta()?>">
                                    <img src="css/icos/<?=$icono?>"/>
                                </a>
                                Respuesta <?=$i?><br />
                                <div class="ministats floatleft">Respondida <?=$respuesta->getContestada()?> </div>
                            </label>
                            <input type="hidden" name="id_respuesta_<?=$i?>"
                                id="id_r_<?=$i?>" value="<?=$respuesta->getIdRespuesta()?>"/>
                            <textarea  class="ckeditor" title="vacio"  name="respuesta_<?=$i?>"
                                id="respuesta_<?=$i?>"><?=$respuesta->getTexto()?></textarea>
                            <input onclick="chkRespuesta(this)" type="checkbox" <?=$cheked?> class="uncheck" name="chkrespuesta_<?=$i?>" id="chkrespuesta_<?=$i?>" />
                            <div class="clear"></div>
                            <? if ($respuesta->getImagen() != null) { ?>
                                <img src="upload/pregunta/<?=$respuesta->getImagen()?>" />
                                <a href="funciones/controlador.php?accion=eliminafoto&id_respuesta=<?=$respuesta->getIdRespuesta()?>&id_anyo=<?=$_GET["id_anyo"]?>&imagen=<?= $respuesta->getImagen() ?>&campo=4">
                                   <img src="css/icos/admindelete.png" />
                                </a>
                                <input name="archivo_respuesta_<?=$i?>" type="hidden" value="<?=$respuesta->getImagen()?>"/>
                            <? } else { ?>
                                <input name="imagen_respuesta_<?=$i?>" type="file" value=""/>
                            <? } ?>
                            <div class="clear"></div>
                            <? } ?>
                        <input type="hidden" name="hidcontrespuestas"  id="hidcontrespuestas" value="<?=$i?>" autocomplete="off" />
                    </div>
                    <input type="submit" value="Guardar Pregunta" />
                    <input type="button" value="+ Añadir Respuesta" id="addrespuestabutton" class="botonclaro" />
                </form>
                <div class="clear"></div>
                <p>Ubicada en los exámenes:</p>
                <div id="ubicada" onclick="expandir();">
                    +
                </div>
            </div>
            <? include 'trozos/pie.php'; ?>
            <div class="clear"></div>
        </div>
    </body>
</html>