<? include 'trozos/session.php';
require_once 'clases/Pregunta.php';
require_once 'clases/Anyo.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/recargaTemario.js?ver=1.3"></script>
        <style type="text/css">
            #EqnEditor { text-align:left;}

            #editorEcuacion{
                float: right;
            }



            #contiene-temario, #contiene-temario_v{
                float: right;
                clear: both;
            }

            .temario{
            }


            .ubicaTema{
                border: 1px solid #1F1F1F;
                border-radius: 5px 5px 5px 5px;
                padding: 3px 10px;
                width: 600px;
                float:left;
                margin-bottom: 10px;
            }

            .modifica, .borra{
                float:left;
                transition:all 0.5s;
                -moz-transition:all 0.5s;
                -ms-transition:all 0.5s;
                -o-transition:all 0.5s;
                -webkit-transition:all 0.5s;
                margin-left: 5px;
                cursor: pointer;
            }

            .modifica:hover, .borra:hover{
                transform:scale(1.5,1.5);
                -moz-transform:scale(1.5,1.5);
                -ms-transform:scale(1.5,1.5);
                -o-transform:scale(1.5,1.5);
                -webkit-transform:scale(1.5,1.5);
            }

            .asociar{
                background: #0f0f0f !important;
                color: #ffffff !important;
                padding: 5px 10px !important;
                margin: 10px !important;
            }

            .muestraTemas{
                float:left;
                transition:all 0.5s;
                -moz-transition:all 0.5s;
                -ms-transition:all 0.5s;
                -o-transition:all 0.5s;
                -webkit-transition:all 0.5s;
                display: none;
                opacity: 0.0;
            }

            .listadmin li{
                cursor: default;
            }


            .listadmin.categoriasli li .show{
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <?
        include 'trozos/encabezado.php';
        ?><div id="content">
            <div class="contplataforma">
                <h1>Añadir pregunta<img src="css/icos/icobloques.png" /></h1>
                <a href="listpreguntas.php?id_anyo=<?= $_GET["id_anyo"]; ?>" class="botonoscuro">&lt;&lt; Atras</a>
                <br /><br />
                <div class="clear"></div>
                <form action="funciones/controlador.php" id="frmcrearpregunta" class="frmadmin" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_anyo" value="<? echo $_GET["id_anyo"]; ?>"/>
                    <input type="hidden" name="accion" value="altaPregunta"/>
                    <input type="hidden" name="id_unidad" value=""/>
                    <label>Codigo</label>
                    <input name="codigo" type="text" class="requerido"/>
                    <div class="clear"></div>
                    <label>Enunciado</label>
                    <textarea class="ckeditor" name="enunciado" title="vacio" class="requerido"></textarea>
                    <div class="clear"></div>
                    <label>Imagen del enunciado</label>
                    <input name="imagen_enunciado" type="file" value=""/>
                    <div class="clear"></div>
                    <label>Anotación</label>
                    <textarea  class="ckeditor" name="anotacion"
                               placeholder="En este apartado se pueden colocar anotaciones o ayudas para el alumno"></textarea>
                    <div class="clear"></div>
                    <label>Imagen Anotación</label>
                    <input name="imagen_anotacion" type="file" value=""/>
                    <div class="clear"></div>

                    <label>Explicación</label>
                    <textarea class="ckeditor" 
                              placeholder="Explicación acerca de porque la respuesta no es correcta"  name="explicacion"></textarea>
                    <div class="clear"></div>
                    <label>Imagen Explicación</label>
                    <input name="imagen_explicacion" type="file" value=""/>
                    <div class="clear"></div>
                    <hr />
                    <label>Video</label>
                    <input name="video" type="text" />
                    <div class="clear"></div>
                    <hr />
                    <label>Temario donde se hubica</label>
                    
                    <div id="contiene-temario">
                        <? $t = 1;
                        if ($_GET["id_anyo"] != "0") {
                            $anyo = Anyo_BD::porIf($_GET["id_anyo"]);
                            ?><div class="temario" id="tem_<? echo $t; ?>">
                                <div class="ubicaTema"><?= $anyo->getNombreCurso()." / ". $anyo->getNombreTema() ?> / 
                                <? echo $anyo->getNombreUnidad() ?> - <? echo $anyo->getNombre(); ?>
                                    <input type="hidden" name="anyo_<? echo $t; ?>" value="<? echo $anyo->getIdAnyo(); ?>"/>
                                </div>
                                <div class="borra" rel="<? echo $t; ?>" onclick="borrarTema(this)"><img src="css/icos/admindelete.png" /></div>
                            </div><?
                        } else {
                            ?>SIN ASOCIAR<? } ?>
                    </div>
                    
                    <input type="hidden" name="ultimoTemario" value="1" id="ultimoTemario" />
                    <div class="clear"></div>
                    <input type="button" class="asociar" value="asociar al temario" id="btn-asociar"/>
                    <div id="muestraTemas">
                    </div>
                    <hr/>
                    
                    <div class="clear"></div>
                    <label>Temario virtual</label>
                    <div id="contiene-temario_v"></div>
                    
                    <div class="clear"></div>
                    <input type="button" class="asociar" value="asociar virtualmente" id="btn-asociar_v"/>
                    <div id="muestraTemas_v">
                    </div>
                    <hr />
                    
                    <div class="clear"></div>
                    <div id="contrespuestasform">
                        <input type="hidden" name="hidcontrespuestas"  id="hidcontrespuestas" value="0" autocomplete="off" />
                    </div>
                    <input type="submit" value="Guardar Pregunta" />
                    <input type="button" value="+ Añadir Respuesta" id="addrespuestabutton" class="botonclaro" />
                </form>
            </div>
            <? include 'trozos/pie.php'; ?>
            <div class="clear"></div>
        </div>
    </body>
</html>
