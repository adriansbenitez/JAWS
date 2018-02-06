<? include 'trozos/session.php';
require_once 'clases/Examen.php';
require_once 'clases/Curso.php';
require_once 'clases/Grupo.php';
seguridad("usuario");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <link type="text/css" rel="stylesheet" href="css/examenes.css"/>
        <script type="text/javascript" src="js/examenes.js?ver=1.3"></script>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".lista-grupos > .boton").click(function(){
                    if($(this).attr("estado") == "0"){
                        $(this).attr("estado", "1");
                        $(this).css({"background": "#d9d9d9"});                        
                    }else{
                        $(this).attr("estado", "0");
                        $(this).css({"background": "#EFEACF"});
                    }
                    
                    var listaGrupos = "";
                    $('.lista-grupos > .boton').each(function(indi, ele){
                        if($(ele).attr('estado') == "1"){
                            if(listaGrupos != ""){
                                listaGrupos += ",";
                            }
                            listaGrupos+=$(ele).attr('rel');
                        }
                    });
                    
                    $('#id_grupo').val(listaGrupos);
                });
            });
        </script>
        <link type="text/css" rel="stylesheet" href="css/calendario.css"/>
        <style type="text/css">
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
                    <input type="hidden" name="accion" value="crearExamen"/>
                    <div id="mensajero"></div>
                    <input type="hidden" name="cursos" id="arrCursos" value=""/>
                    <input type="hidden" name="temas" id="arrTemas" value=""/>
                    <input type="hidden" name="unidades" id="arrUnidades" value=""/>
                    <input type="hidden" name="anyos" id="arrAnyos" value=""/>
                    <input type="hidden" name="listaAlumnos" id="listaAlumnos"/>
                    
                    <? if($_SESSION["usuario"]->getNivel()==1){ ?>
                        <input type="hidden" name="id_grupo" id="id_grupo"/>
                    <? } ?>
                    
                        <label>Nombre de examen</label><input name="titulo" type="text" class="requerido" maxlength="90"/>
                    <div class="clear"></div>
                    <label>Tiempo Máximo<br/>(Minutos 0 sin límite)</label>
                    <input type="text" name="tiempo" value="0" class="requerido"/>

                    <div class="clear"></div>
                    <label>Número de preguntas</label><input class="auto" name="numpreguntas" type="text" title="vacio" />
                    <div class="clear"></div>
                    <? if($_SESSION["usuario"]->getNivel()==1){ ?>
                        <label>Disponible desde<br/>(De estár vacío es la fecha de hoy)</label>
                        <input type="text" name="fecha" class="fecha" value=""/>
                        <div class="clear"></div>
                    
                        <label>Disponible hasta<br/>(De estár vacío es indefinido)</label>
                        <input type="text" name="fecha_fin" class="fecha" value=""/>
                        <div class="clear"></div>
                    <? } ?>
                    
                    <label>Dirigido a</label>
                    <div id="alumnosElegidos"></div>

                    <div class="clear"></div>
                    <select id="lstperfiles" name="lstperfiles">
                        <? if ($_SESSION["usuario"]->getNivel() == 1) { ?>

                            <option value="0">--Selecciona--</option>
                            <? $listaUsuarios = Usuario_BD::listarUsuarios();
                            foreach ($listaUsuarios as $usuario) {
                                ?>
                                <option value="<?=$usuario->getIdUsuario()?>">
                                <?=$usuario->getApellido()?>, <?=$usuario->getNombre()?>
                                </option>
                            <? }
                            } else {
                            ?>
                            <option value="privado">PRIVADO</option>
                        <? } ?>
                    </select>
                    <div class="clear15"></div>
                    <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                        <h3>Grupo: </h3>
                        <? $listaGrupos = Grupo_BD::listar();
                        $grupo = new Grupo(null, null, null); ?>
                        <div class="lista-grupos">
                            <? foreach ($listaGrupos as $grupo) { ?>
                                <div class="boton" rel="<?=$grupo->getIdGrupo()?>"
                                    estado="0"><?=$grupo->getNombre()?></div>
                            <? } ?>
                        </div>
                        <div class="clear"></div>
                    <? } ?>
                    
                    <hr/>
                    <input type="button" value="Crear Examen" class="botonEnviar"/>
                    <div class="clear"></div>
                    <? $listaCursos = Curso_BD::paraExamen($_SESSION["usuario"]);
                    $curso = new Curso(null, null, null); ?>
                        <ul class="listadmin categoriasli">
                            <? foreach ($listaCursos as $curso){ ?><li>
                                <img src="css/icos/icoplus.png" height="23" border="0" class="show lista-cursos" align="absmiddle"
                                     onclick="expandeCursos(this)" rel="<? echo $curso->getIdCurso(); ?>"/>
                                    <span class="contador">(Nº Preguntas:<? echo $curso->getTotalPreguntas(); ?>)</span>
                                    <? echo $curso->getNombre(); ?>
                                    <input type="checkbox" id="Curso<? echo $curso->getIdCurso(); ?>"
                                           value="<? echo $curso->getIdCurso(); ?>" class="check-curso" onclick="cambiaCurso(this)"
                                           preg="<? echo $curso->getTotalPreguntas(); ?>"/>
                                           </li>
                                        <div id="dentroCurso<? echo $curso->getIdCurso(); ?>"></div>
                            <? } ?>
                        </ul>
                    <div class="clear"></div>
                </form>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <script type="text/javascript">
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
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
