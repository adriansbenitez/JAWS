<? include 'trozos/session.php';
require_once 'clases/Examen.php';
require_once 'clases/Grupo.php';
require_once 'clases/Pregunta.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/recargasAdmin.js?ver=1.1"></script>
        <script type="text/javascript" src="js/buscaPreguntas.js?ver=1.1"></script>
        <link type="text/css" rel="stylesheet" href="css/calendario.css"/>
        <link type="text/css" rel="stylesheet" href="css/examenesPersonalizados.css?ver=1.1"/>
        <style type="text/css">			
            #alumnosElegidos .alumnoAgregado{
                border: 1px solid #666600;
                color: #000000;
                background: #fafafa;
                display: table;
                width: 100%;
            }

            #alumnosElegidos .alumnoAgregado .ubicaTema{
                float: left;
                margin: 10px;
            }

            #alumnosElegidos .alumnoAgregado .borraAlumno {
                float: right;
                margin: 10px 20px;
                cursor: pointer;
                transition:all 0.3s;
                -moz-transition:all 0.3s;
                -ms-transition:all 0.3s;
                -o-transition:all 0.3s;
                -webkit-transition:all 0.3s;
            }

            #alumnosElegidos .alumnoAgregado .borraAlumno:hoover{
                transform:scale(1.5,1.5);
                -moz-transform:scale(1.5,1.5);
                -ms-transform:scale(1.5,1.5);
                -o-transform:scale(1.5,1.5);
                -webkit-transform:scale(1.5,1.5);
            }
            
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
                
                $('#lstperfiles').change(function(){
                    setTimeout(llenar, 100);
                });
            });

            function quitarAlumno(aQuitar) {
                $(aQuitar).remove();
                llenar();
            }

            function llenar() {
                cadena = ""
                $('.borraAlumno').each(function(indi, ele) {
                    cadena += $(ele).attr('rel') + ",";
                });

                cadena = cadena.substring(0, cadena.length - 1)

                $('#listaAlumnos').attr('value', cadena);
            }
        </script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <? $examen = new Examen(null, null, null, null, null);
            $examen = Examen_BD::porId($_GET["id_examen"]); ?>
            <div class="contplataforma">
                <h1>Editar Exámenes <img src="css/icos/icoexamenes.png" /></h1>
                <a href="listexamenes.php" class="botonoscuro">&lt;&lt; Atras</a>
                <hr />
                <div class="clear"></div>
                <? $listaUsuarios = "";
                $bd = new BD();
                $bd->setConsulta("select e.id_examen, u.id_usuario, concat(u.apellido,', ',u.nombre) as nombre from usuarios_examenes e inner join usuarios u on u.id_usuario = e.id_usuario where e.id_examen = ?");
                $bd->ejecutar($examen->getIdExamen());
                while ($it = $bd->resultado()) {
                    if($listaUsuarios != ""){
                        $listaUsuarios .=",";
                    }
                    $listaUsuarios.= $it["id_usuario"];
                } ?>
                <form action="funciones/controlador.php" id="frmcrearexamen" class="frmadmin" method="post">
                    <input type="hidden" name="accion" value="editar_examen"/>
                    <input type="hidden" name="id_examen" value="<?= $examen->getIdExamen() ?>"/>
                    <input type="hidden" name="listaAlumnos" id="listaAlumnos" value="<?= $listaUsuarios?>"/>
                    <input type="hidden" name="id_grupo" id="id_grupo" value="<?=$examen->listaGruposComas() ?>"/>

                    <label>Nombre de examen</label>
                    <input name="titulo" id="titulo" type="text" title="vacio" value="<?= $examen->getTitulo() ?>" />
                    <div class="clear"></div>

                    <label>Tiempo Máximo<br/>(Minutos 0 sin límite)</label>
                    <input type="text" name="tiempo" value="<?= $examen->getTiempo() ?>"/>
                    <div class="clear"></div>

                    <label>Disponible desde</label>
                    <input type="text" name="fecha" class="fecha" value="<?=$examen->getFecha()->conFormatoSimple("/") ?>"/>
                    <div class="clear"></div>

                    <label>Disponible hasta<br/>(De estár vacío es indefinido)</label>
                    <? if($examen->getFechaFin() != null){
                        $fechaFin = $examen->getFechaFin()->conFormatoSimple("/");
                    }else{
                        $fechaFin = "";
                    } ?>
                    <input type="text" name="fecha_fin" class="fecha" value="<?=$fechaFin?>"/>
                    <div class="clear"></div>
                    <hr />

                    <div class="clear"></div>
                    <label>Dirigido a</label>
                    <div id="alumnosElegidos">
                        <?
                        $bd = new BD();
                        $bd->setConsulta("select e.id_examen, u.id_usuario, concat(u.apellido,', ',u.nombre) as nombre from usuarios_examenes e inner join usuarios u on u.id_usuario = e.id_usuario where e.id_examen = ?");
                        $bd->ejecutar($examen->getIdExamen());
                        while ($it = $bd->resultado()) {
                            ?>
                            <div class="alumnoAgregado" id="alumnoAgregado_<?= $it["id_usuario"] ?>">
                                <div class="ubicaTema"><?= $it["nombre"] ?></div>
                                <div class="borraAlumno" rel="<?= $it["id_usuario"] ?>"
                                     onClick="quitarAlumno('#alumnoAgregado_<?= $it["id_usuario"] ?>')">
                                    <img src="css/icos/admindelete.png" /></div>
                            </div>
                        <? } ?>
                    </div>
                    <div class="clear"></div>
                    <hr />
                    <h2>Preguntas:</h2>
                    <div id="preguntasElegidas">
                        <? $listaPreguntas = Pregunta_BD::preguntasPorExamen($examen->getIdExamen()); 
                        $totalPreguntas = count($listaPreguntas);
                        $textoListaPreguntas = "";
                        $primero = true;
                        foreach($listaPreguntas as $pregunta){
                            if($primero){
                                $primero = false;
                            }else{
                                $textoListaPreguntas.=",";
                            }
                            $textoListaPreguntas.=$pregunta->getIdPregunta()."@".$pregunta->getIdAnyo()?>
                        <div class="preguntaAgregada" id="preguntaAgregada_<?=$pregunta->getIdPregunta()?>">
                            <div class="ubicaTema"><?=$pregunta->getCodigo()?><br/>
                                <?=$pregunta->getEnunciado()?><br/>
                                <?=$pregunta->getNombreAnyo()?>
                            </div>
                            <div class="borraPregunta" rel="<?=$pregunta->getIdPregunta()?>@<?=$pregunta->getIdAnyo()?>" onClick="quitarPregunta('#preguntaAgregada_<?=$pregunta->getIdPregunta()?>')">
                                <img src="css/icos/admindelete.png" />
                            </div>
                        </div>
                        <? } ?>
                    </div>
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
                    <div id="cuentaPreguntas" rel="<?=$totalPreguntas?>"><?=$totalPreguntas?></div>
                    <div class="clear"></div>
                    <hr />

                    <label>Selección de usuarios</label>
                    <select id="lstperfiles"  name="lstperfiles">
                        <option value="0" selected>---AGREGAR---</option>
                        <?
                        $bd->setConsulta("select id_usuario, nombre, apellido from usuarios order by apellido");
                        $bd->ejecutar();
                        while ($it = $bd->resultado()) {
                            ?>
                            <option value="<?= $it["id_usuario"] ?>"><?= $it["apellido"] ?>, <?= $it["nombre"] ?></option>
                        <? } ?>
                    </select>
                    <div class="clear"></div>
                    
                    <h3>Grupo: </h3>
                    <? $listaGrupos = Grupo_BD::listar();
                    $grupo = new Grupo(null, null, null); ?>
                    <div class="lista-grupos">
                    <? foreach ($listaGrupos as $grupo) { ?>
                        <div class="boton" rel="<?= $grupo->getIdGrupo() ?>"
                            <? $encontrado = false;
                            foreach ($examen->getListaGrupos() as $idGrupo){
                                if($grupo->getIdGrupo() == $idGrupo){
                                    echo 'estado="1" style="background: #d9d9d9;"';
                                    $encontrado = true;
                                }
                            }

                            if(!$encontrado){
                                echo 'estado="0"';
                            }
                        ?>><?=$grupo->getNombre()?></div>
                    <? } ?>
                    </div>
                    <div class="clear"></div>
                    <input type="hidden" name="listaPreguntas" id="preguntasExamen" value="<?=$textoListaPreguntas?>"/>
                    
                    <input type="submit" value="Confirmar Edición" />
                </form>
            </div>
        </div>
        <? include 'trozos/pie.php'; ?>
        <script type="text/javascript">
            $('#frmcrearexamen').submit(function(){
                var cadena = "";
                var primero = true;
                $('.borraPregunta').each(function(ind, ele) {
                    if(primero){
                        primero = false;
                    }else{
                        cadena += ",";
                    }
                    cadena += $(ele).attr('rel');
                });
                
                $('#preguntasExamen').val(cadena);
            });

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
    </body>
</html>