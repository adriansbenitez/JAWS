<?
include 'trozos/session.php';
include 'trozos/menuPerfil.php';
require_once 'clases/Calendario.php';
require_once 'clases/Usuario.php';
require_once 'clases/Curso.php';
require_once 'clases/Grupo.php';
seguridad("nodemo");

$_SESSION["filtro_grupo"] = null;
$_SESSION["filtro_curso"] = null;
$_SESSION["filtro_usuarios"] = null;

$agenda = Calendario_BD::porId($_POST["id_calendario"]);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Editar entrada del Calendario <img src="css/icos/icocalendario.png" alt="calendario"/></h1>
                <div class="colun2-3 perfilcolumn">
                    <div>
                    </div>
                    <div>
                        <? $cal = new Calendario();
                        $cal->importar(); ?>
                        <div class="clear15"></div>
                        <h2>Editar anotación</h2>
                        <div id="calendarioEventos">
                            <form method="post" action="funciones/Controlador.php">
                                <input type="hidden" name="accion" value="modificar_calendario"/>
                                <input type="hidden" name="id_calendario" value="<?=$agenda->getIdCalendario(); ?>" />
                                
                                <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                                <input type="hidden" name="lista_usuarios" value="" id="lista_usuarios"/>
                                <input type="hidden" name="id_curso" value="<?=$agenda->getIdCurso()?>" id="id_curso"/>
                                <input type="hidden" name="id_grupo" value="<?=$agenda->getIdGrupo()?>" id="id_grupo"/>
                                <? } ?>
                                
                                <div class="izquierda">Título</div>
                                <div class="derecha"><input type="text" name="titulo" value="<?=$agenda->getTitulo()?>"/></div>
                                <div class="clear"></div>

                                <div class="izquierda">Día de inicio</div>
                                <div class="derecha"><input type="text" name="fecha_inicio" class="fecha" value="<?=$agenda->getPrimerDia()->conFormatoSimple("/")?>"/></div>
                                <div class="clear"></div>
                                
                                <div class="izquierda">Día de fin</div>
                                <div class="derecha"><input type="text" name="fecha_fin" class="fecha" value="<?=$agenda->getUltimoDia()->conFormatoSimple("/")?>"/></div>
                                <div class="clear"></div>
                                
                                <div class="izquierda">Hora</div>
                                <div class="derecha">
                                    <select name="hora">
                                        <? include 'trozos/option_horas.php'; ?>
                                    </select>
                                </div>
                                <div class="clear"></div>
                                
                                <div class="izquierda">¿Es alerta?</div>
                                <div class="derecha"><input type="checkbox" name="alerta" <?
                                    if($agenda->getAlerta()){
                                        echo ' checked="checked"';
                                    } ?>/></div>
                                <div class="clear"></div>
                                
                                <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                                    <div class="izquierda">Color</div>
                                    <div class="derecha">
                                        <input type="text" name="color" value="<?=$agenda->getColor()?>" class="color"/>
                                    </div>
                                    <div class="clear"></div>
                                <? } ?>
                                
                                <div class="izquierda">Texto</div>
                                <div class="derecha"><textarea type="text" name="texto" class="editor"><?=$agenda->getTexto()?></textarea></div>
                                <div class="clear"></div>
                                
                                <input type="submit" class="botonoscuro derecha alta-cal" value="Modificar" style="margin: 30px auto 10px;"/>
                            </form>
                        </div>
                        <div class="clear15"></div>
                        <h3>Curso</h3>
                        <? if($_SESSION["usuario"]->getNivel() == 1){
                        $listaCursos = Curso_BD::listarParaCalendario();
                        $curso = new Curso(null, null, null);?>
                        <div class="lista-cursos">
                            <? foreach ($listaCursos as $curso){ ?>
                            <div class="boton" rel="<?=$curso->getIdCurso()?>"
                                 <? if($curso->getIdCurso() == $agenda->getIdCurso()){
                                     echo 'estado="1" style="background: #d9d9d9;"';
                                 }else{
                                     echo 'estado="0"';
                                 } ?>><?=$curso->getNombre()?></div>
                            <? } ?>
                        </div>
                        <div class="clear"></div>
                        <h3>Grupo</h3>
                        <? $listaGrupos = Grupo_BD::listar();
                        $grupo = new Grupo(null, null, null); ?>
                        <div class="lista-grupos">
                            <? foreach ($listaGrupos as $grupo) { ?>
                                <div class="boton grupo" rel="<?= $grupo->getIdGrupo() ?>"
                                    <? if($grupo->getIdGrupo() == $agenda->getIdGrupo()){
                                         echo 'estado="1" style="background: #d9d9d9;"';
                                    }else{
                                        echo 'estado="0"';
                                    } ?>><?= $grupo->getNombre() ?></div>
                                 <? } ?>
                        </div>
                        <div class="clear"></div>
                        <?
                        $listaUsuarios = Usuario_BD::listarParaCalendario();
                        $total = count($listaUsuarios);
                        $cuenta = 0;
                        $parte = 1;?>
                        <div class="lista-usuarios-cal">
                            <div class="parte1">
                        <? foreach ($listaUsuarios as $usuario){
                            $cuenta++;
                            if($cuenta > ($total/2) && $parte!=2){
                              $parte = 2;?>
                                </div>
                                <div class="parte2">
                            <? } ?>
                            <div class="usuario">
                                <?=$usuario->getApellido()?>, <?=$usuario->getNombre()?>
                                <input type="checkbox"
                                       <? foreach ($agenda->getUsuario() as $marcado){
                                           if($marcado == $usuario->getIdUsuario()){
                                               echo ' checked="checked"';
                                           }
                                       } ?>
                                       value="<?=$usuario->getIdUsuario()?>"/>
                            </div>
                        <? } ?>
                            </div>
                        </div>
                        <? } ?>

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