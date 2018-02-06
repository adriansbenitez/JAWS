<? include 'trozos/session.php';
require_once 'clases/Examen.php';
require_once 'clases/Curso.php';
require_once 'clases/Grupo.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <script type="text/javascript" src="js/buscaPreguntas.js?ver=1.1"></script>
        <link type="text/css" rel="stylesheet" href="css/examenesPersonalizados.css?ver=1.1"/>
        <title>Plataforma de Formacion Inspiracle</title>
        
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
        <script type="text/javascript">
            $(document).ready(function() {
                $(".lista-grupos > .boton").click(function(){
                    if($(this).attr("estado") == "0"){
                        $(".lista-grupos > .boton").attr("estado","0");
                        $(".lista-grupos > .boton").css({"background": "#EFEACF"});
                        
                        $(this).attr("estado", "1");
                        $(this).css({"background": "#d9d9d9"});
                        
                        $('#id_grupo').val($(this).attr("rel"));
                    }else{
                        $(this).attr("estado", "0");
                        $(this).css({"background": "#EFEACF"});
                        $('#id_grupo').val("");
                    }
                });
                
            });
        </script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Crear Exámenes <img src="css/icos/icoexamenes.png" /></h1>
                <a href="listexamenes.php" class="botonoscuro">&lt;&lt; Atras</a>
                <hr />
                <div class="clear"></div>
                <form action="funciones/controlador.php" id="frmcrearexamen" class="frmadmin" method="post">
                    <input type="hidden" name="accion" value="crearExamenPersonalizado"/>
                    <div id="mensajero"></div>
                    <input type="hidden" name="listaPreguntas" id="preguntasExamen"/>
                    <input type="hidden" name="listaAlumnos" id="listaAlumnos"/>
                    <input type="hidden" name="id_grupo" id="id_grupo"/>
                    
                    <label>Nombre de examen</label><input name="titulo" type="text"/>
                    <div class="clear"></div>
                    <label>Tiempo Máximo<br/>(Minutos 0 sin límite)</label>
                    <input type="text" name="tiempo" value="0"/>
                    <div class="clear"></div>
                    <div class="clear"></div>
                    
                    <label>Disponible desde<br/>(De estár vacío es la fecha de hoy)</label>
                    <input type="text" name="fecha" class="fecha" value=""/>
                    <div class="clear"></div>
                    
                    <label>Disponible hasta<br/>(De estár vacío es indefinido)</label>
                    <input type="text" name="fecha_fin" class="fecha" value=""/>
                    <div class="clear"></div>                    
                    
                    <label>Dirigido a</label>
                    <div id="alumnosElegidos"></div>

                    <div class="clear"></div>
                    <select id="lstperfiles"  name="usuarios">
                        <? $listaUsuarios = Usuario_BD::listarUsuarios();
                        foreach ($listaUsuarios as $usuario) { ?>
                            <option value="<? echo $usuario->getIdUsuario(); ?>"><? echo $usuario->getApellido(); ?>, <? echo $usuario->getNombre(); ?></option>
                        <? } ?>
                    </select>
                    <div class="clear"></div>
                    
                    <h3>Grupo: </h3>
                    <? $listaGrupos = Grupo_BD::listar();
                    $grupo = new Grupo(null, null, null); ?>
                    <div class="lista-grupos">
                    <? foreach ($listaGrupos as $grupo) { ?>
                        <div class="boton" rel="<?= $grupo->getIdGrupo() ?>"
                            estado="0"><?= $grupo->getNombre() ?></div>
                    <? } ?>
                    </div>
                    <div class="clear"></div>

                    <hr />
                    <div id="preguntasElegidas">
                    </div>
                    <div class="clear"></div>

                    <hr />
                    <label>Codigo</label>
                    <input type="text" name="txt_codigo" id="txt_codigo"/>
                    <div class="clear"></div>

                    <label>Enunciado de pregunta</label>
                    <input type="text" name="txt_en_pregunta"  id="txt_en_pregunta"/>
                    <div class="clear"></div>

                    <label>Enunciado de respuesta</label>
                    <input type="text" name="txt_en_respuesta" id="txt_en_respuesta"/>
                    <div class="clear"></div>

                    <input type="button" value="Buscar Preguntas" id="buscaPreguntas"/>
                    <div class="clear"></div>
                    <hr/>
                    
                    <div id="listaPreguntas">
                    </div>
                    <div class="clear"></div>
                    <div id="cuentaPreguntas" rel="0">0
                    </div>
                    <div class="clear"></div>
                    <hr />

                    <div class="clear"></div>
                    <input type="button" value="Crear Examen" class="botonEnviar"/>
                </form>
                <?include 'trozos/leyenda.php';?>
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