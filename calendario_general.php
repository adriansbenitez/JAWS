<?
include 'trozos/session.php';
include 'trozos/menuPerfil.php';
require_once 'clases/Calendario.php';
require_once 'clases/Usuario.php';
require_once 'clases/Curso.php';
require_once 'clases/Grupo.php';
seguridad("admin");

$_SESSION["filtro_grupo"] = null;
$_SESSION["filtro_curso"] = null;
$_SESSION["filtro_usuarios"] = null;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
            .colun2-3.perfilcolumn > div:first-child {
                margin-left: -30px !important;
                position: relative !important;
                float: left !important;
                top: 0px !important;
            }

            .lista-usuarios-cal > .parte1, .lista-usuarios-cal > .parte2 {
                width: 240px !important;
                float: left !important;
                font-size: 14px;
            }

            .lista-usuarios-cal{
                border: none !important;
            }

            .lista-cursos > .boton, .lista-grupos > .boton{
                font-size: 15px;
                margin: 10px 0 !important;
            }

            .lista-cursos > .boton:nth-child(even), .lista-grupos > .boton:nth-child(even){
                float: right;
            }
        </style>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Calendario <img src="css/icos/icocalendario.png" alt="calendario"/></h1>
                <div class="colun2-3 perfilcolumn">
                    <div>
                        <h3>Curso</h3>
                        <? $listaCursos = Curso_BD::listarParaCalendario();
                        $curso = new Curso(null, null, null); ?>
                        <div class="lista-cursos">
                            <? foreach ($listaCursos as $curso) { ?>
                                <div class="boton" rel="<?= $curso->getIdCurso() ?>"
                                     estado="0"><?= $curso->getNombre() ?></div>
                                 <? } ?>
                        </div>
                        <div class="clear"></div>
                        <h3>Grupo</h3>
                        <? $listaGrupos = Grupo_BD::listar();
                        $grupo = new Grupo(null, null, null); ?>
                        <div class="lista-grupos">
                            <? foreach ($listaGrupos as $grupo) { ?>
                                <div class="boton" rel="<?= $grupo->getIdGrupo() ?>"
                                     estado="0"><?= $grupo->getNombre() ?></div>
                                 <? } ?>
                        </div>
                        <div class="clear"></div>
                        
                        <? $listaUsuarios = Usuario_BD::listarParaCalendario();
                        $total = count($listaUsuarios);
                        $cuenta = 0;
                        $parte = 1;
                        ?>
                        <div class="lista-usuarios-cal">
                            <div class="parte1">
                                <?
                                foreach ($listaUsuarios as $usuario) {
                                    $cuenta++;
                                    if ($cuenta > ($total / 2) && $parte != 2) {
                                        $parte = 2;
                                        ?>
                                    </div>
                                    <div class="parte2">
                                    <? } ?>
                                    <div class="usuario">
                                        <?= $usuario->getApellido() ?>, <?= $usuario->getNombre() ?>
                                        <input type="checkbox" class="inter_<?= $usuario->getInteresado() ?>"
                                               value="<?= $usuario->getIdUsuario() ?>"/>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <?
                        $cal = new Calendario();
                        $cal->importar();
                        ?>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $(".lista-usuarios-cal .usuario input,"+
                                        " .lista-grupos .boton,"+
                                        " .lista-cursos .boton").click(function(){
                                    actualizaCalendario();
                                });
                            });
                            
                            function actualizaCalendario(){
                                var cadena = "";
                                $('.lista-usuarios-cal .usuario input').each(function(indice, ele){
                                    if($(ele).attr('checked') == 'checked'){
                                        cadena+=$(ele).attr('value')+",";
                                    }
                                });
                                cadena = cadena.substring(0, cadena.length-1);

                                $.get('clases/Calendario.php',
                                    {"lista-usuarios": cadena,
                                    "id_curso" : $('#id_curso').val(),
                                    "id_grupo" : $('#id_grupo').val(),
                                    "filtrar": ""}, function(respuesta){
                                    $("#calendario").html(respuesta);
                                });
                            }
                        </script>
                        <div id="calendario">
                            <? $cal->montar(); ?>
                        </div>
                        <div class="clear15"></div>
                        <h2>Nueva anotación</h2>
                        <div id="calendarioEventos">
                            <form method="post" action="funciones/Controlador.php">
                                <input type="hidden" name="accion" value="alta_calendario"/>
                                <input type="hidden" name="lista_usuarios" value="" id="lista_usuarios"/>
                                <input type="hidden" name="id_curso" value="" id="id_curso"/>
                                <input type="hidden" name="id_grupo" value="" id="id_grupo"/>

                                <div class="izquierda">Título</div>
                                <div class="derecha"><input type="text" name="titulo"/></div>
                                <div class="clear"></div>

                                <div class="izquierda">Día de inicio</div>
                                <div class="derecha"><input type="text" name="fecha_inicio" class="fecha"/></div>
                                <div class="clear"></div>

                                <div class="izquierda">Día de fin</div>
                                <div class="derecha"><input type="text" name="fecha_fin" class="fecha"/></div>
                                <div class="clear"></div>

                                <div class="izquierda">Hora</div>
                                <div class="derecha">
                                    <select name="hora">
                                        <? include 'trozos/option_horas.php'; ?>
                                    </select>
                                </div>
                                <div class="clear"></div>

                                <div class="izquierda">¿Es alerta?</div>
                                <div class="derecha"><input type="checkbox" name="alerta"/></div>
                                <div class="clear"></div>

                                <? if ($_SESSION["usuario"]->getNivel() == 1) { ?>
                                    <div class="izquierda">Color</div>
                                    <div class="derecha"><input type="text" name="color" class="color"/></div>
                                    <div class="clear"></div>
                                <? } ?>

                                <div class="izquierda">Texto</div>
                                <div class="derecha"><textarea type="text" name="texto" class="editor"></textarea></div>
                                <div class="clear"></div>

                                <input type="submit" class="botonoscuro derecha alta-cal" value="Alta" style="margin: 30px auto 10px;"/>
                            </form>
                        </div>
                        <div class="clear15"></div>

                        <script type="text/javascript">
                            /*
                             tinymce.init({
                             selector: ".editor",
                             theme: "modern",
                             language: 'es',
                             plugins: [
                             "advlist autolink link charmap preview hr",
                             "wordcount visualblocks visualchars code insertdatetime",
                             "table contextmenu directionality emoticons template paste textcolor"
                             ],
                             content_css: "css/content.css",
                             toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | preview | forecolor backcolor emoticons",
                             style_formats: [
                             {title: 'Bold text', inline: 'b'},
                             {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                             {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                             {title: 'Example 1', inline: 'span', classes: 'example1'},
                             {title: 'Example 2', inline: 'span', classes: 'example2'},
                             {title: 'Table styles'},
                             {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                             ]
                             
                             });*/

                            $.datepicker.regional['es'] = {
                                closeText: 'Cerrar',
                                prevText: 'Previo',
                                nextText: 'Próximo',
                                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                                    'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                monthStatus: 'Ver otro mes', yearStatus: 'Ver otro año',
                                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],
                                dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                dateFormat: 'dd/mm/yy', firstDay: 0,
                                initStatus: 'Selecciona la fecha', isRTL: false};
                            $.datepicker.setDefaults($.datepicker.regional['es']);
                            $(".fecha").datepicker({dateFormat: 'dd/mm/yy'});
                        </script>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>

        <? include 'trozos/pie.php'; ?>
    </body>
</html>