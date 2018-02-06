<? include 'trozos/session.php';
require_once 'clases/Curso.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
            .listaCurso{
                float: none;
                margin: 10px auto;
                display: table;
                width: 730px;
            }

            .listaCurso .elemento{
                float: left;
                padding: 4px 0;
                border: 1px solid #6D4D1C;
                color: #6D4D1C;
                width: 220px;
                text-align: center;
                border-radius: 10px;
                margin: 0 20px 20px 0;	
            }

            .listaCurso .elemento:hover, .listaCurso .elemento.selected{
                color: #ffffff;
                background: #6D4D1C;
            }
        </style>
        <script type="text/javascript" src="js/dist/jquery.jqplot.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.pieRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.donutRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.barRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.categoryAxisRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.pointLabels.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.logAxisRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.canvasTextRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.canvasAxisLabelRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.canvasAxisTickRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.dateAxisRenderer.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.highlighter.min.js?ver=1.1"></script>
        <script type="text/javascript" src="js/dist/plugins/jqplot.cursor.min.js?ver=1.1"></script>
        <link rel="stylesheet" type="text/css" href="js/dist/jquery.jqplot.min.css" />
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Estadísticas Globales <img src="css/icos/icoperfil.png" /></h1>
                <hr />
                <h2>Filtro de fechas</h2>
                <form action="#" class="frmadmin" method="get">
                    <? if(isset($_GET["id_curso"])){ ?>
                        <input type="hidden" name="id_curso" value="<?=$_GET["id_curso"]?>"/>
                    <? } ?>
                    <? if(isset($_GET["entidad"])){ ?>
                        <input type="hidden" name="entidad" value="<?=$_GET["entidad"]?>"/>
                    <? } ?>
                    <label>Fecha Inicio</label>
                    <input type="text" name="fecha_inicio" class="fecha" value="<?=$_GET["fecha_inicio"]?>" id="fecha_inicio"/>
                    <div class="clear"></div>

                    <label>Fecha Fin</label>
                    <input type="text" name="fecha_fin" class="fecha" value="<?=$_GET["fecha_fin"]?>" id="fecha_fin"/>
                    <div class="clear"></div>
                    
                    <input type="submit" value="filtrar"/>
                    <div class="clear"></div>
                </form>
                <hr />
                <h2>Filtro de cursos</h2>
                <div class="listaCurso">
                    <?
                    $listaCursos = Curso_BD::listar();
                    $curso = new Curso(null, null, null);
                    
                    if(!empty($_GET["fecha_inicio"])){
                        $arrFecha = explode("/", $_GET["fecha_inicio"]);
                        $fechaInicio = new Fecha($arrFecha[2] . "-" . $arrFecha[1] . "-" . $arrFecha[0] . " 00-00-00");
                    }else{
                        $fechaInicio = Fecha::nuevaFecha(1, 1, 2000, 0);
                    }
                    
                    if(!empty($_GET["fecha_fin"])){
                        $arrFecha = explode("/", $_GET["fecha_fin"]);
                        $fechaFin = new Fecha($arrFecha[2] . "-" . $arrFecha[1] . "-" . $arrFecha[0] . " 00-00-00");
                    }else{
                        $fechaFin = Fecha::nuevaFecha(31, 12, 2030, 0);
                    }
                    
                    
                    ?>
                    <a href="globalstats.php"><div class="elemento <?
                        if (!isset($_REQUEST["id_curso"])) {
                            echo "selected";
                        }
                        ?>">Todos</div></a>
                        <? foreach ($listaCursos as $curso) { ?>
                        <a href="globalstats.php?id_curso=<? echo $curso->getIdCurso(); ?>">
                            <div class="elemento <?
                                 if (isset($_REQUEST["id_curso"]) && $_REQUEST["id_curso"] == $curso->getIdCurso()) {
                                     echo "selected";
                                 }
                                 ?>"><? echo $curso->getNombre(); ?></div>
                        </a>
<? } ?>
                </div>
                <div class="clear"></div>

                <?
                $bd = new BD();
                $nivel = 0;
                $entidad = null;
                if (isset($_REQUEST["id_curso"])) {
                    $totalok = 0;
                    $totalko = 0;
                    $totalnsnc = 0;

                    $sqlGraf1 = "select (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join resultados rl on rl.id_resultado = rr.id_resultado where t.id_curso = ? and re.correcta = 1 and rl.fecha >= ? and rl.fecha <= ?) correctas, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join resultados rl on rl.id_resultado = rr.id_resultado where t.id_curso = ? and re.correcta != 1 and rl.fecha >= ? and rl.fecha <= ?) erradas, (select count(*) from examenes_preguntas ep inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema where t.id_curso = ? and r.fecha >= ? and r.fecha <= ?) todas";
                    $parametros = array($_REQUEST["id_curso"], $fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora(), $_REQUEST["id_curso"], $fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora(), $_REQUEST["id_curso"], $fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora());
                    
                    $nivel = 1;
                    if (isset($_REQUEST["entidad"])) {
                        $ent = explode("_", $_REQUEST["entidad"]);
                        if ($ent[0] == "t") {
                            $sqlGraf1 = "select (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join resultados rl on rl.id_resultado = rr.id_resultado where u.id_tema = ? and re.correcta = 1 and rl.fecha >= ? and rl.fecha <= ?) correctas, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad  inner join resultados rl on rl.id_resultado = rr.id_resultado where u.id_tema = ? and re.correcta != 1 and rl.fecha >= ? and rl.fecha <= ?) erradas, (select count(*) from examenes_preguntas ep inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad where u.id_tema = ? and r.fecha >= ? and r.fecha <= ?) todas";
                            $nivel = 2;
                            require_once 'clases/Tema.php';
                            $tema = Tema_BD::porId($ent[1]);
                            ?><h2 style="text-align: center">Tema <?=$tema->getNombre()?><br/>El tema contiene <?=$tema->getTotalPreguntas()?> Preguntas</h2><?
                        } else {
                            $sqlGraf1 = "select (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join resultados rl on rl.id_resultado = rr.id_resultado where a.id_unidad = ? and re.correcta = 1 and rl.fecha >= ? and rl.fecha <= ?) correctas, (select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas re on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join resultados rl on rl.id_resultado = rr.id_resultado where a.id_unidad = ? and re.correcta != 1 and rl.fecha >= ? and rl.fecha <= ?) erradas, (select count(*) from examenes_preguntas ep inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo where a.id_unidad = ? and r.fecha >= ? and r.fecha <= ?) todas";
                            $nivel = 3;
                            require_once 'clases/Unidad.php';
                            $unidad = Unidad_BD::porId($ent[1])
                            ?><h2 style="text-align: center">Unidad: <?=$unidad->getNombre()?><br/>La unidad contiene <?=$unidad->getTotalPreguntas()?> Preguntas</h2><?
                        }
                        $parametros = array($ent[1], $fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora(), $ent[1], $fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora(), $ent[1], $fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora());
                    }

                    $bd->setConsulta($sqlGraf1);
                    $bd->ejecutar($parametros);
                    if ($it = $bd->resultado()) {
                        $totalok = $it["correctas"];
                        $totalko = $it["erradas"];
                        $totalnsnc = $it["todas"] - ($it["correctas"] + $it["erradas"]);
                    }
                    //Set listaGraf2 = Server.CreateObject("ADODB.Recordset")
                    //dim parGraf2(1)
                    //parGraf2(0) = Request("id_curso")
                    //consultaBDRS sqlGraf2, parGraf2, listaGraf2
                }

                $sqlGraf2 = "select t.id_tema, t.nombre, null id_unidad, null nombreUnidad from temas t inner join cursos c on c.id_curso = t.id_curso union select t.id_tema, null, u.id_unidad, u.nombre from temas t inner join unidades u on u.id_tema = t.id_tema inner join cursos c on c.id_curso = t.id_curso where c.id_curso = ? order by id_tema, nombre desc";

                $bd2 = new BD();
                $bd2->setConsulta($sqlGraf2);
                if (isset($_REQUEST["id_curso"])) {
                    $bd2->ejecutar($_REQUEST["id_curso"]);
                } else {
                    $bd2->ejecutar(0);
                }
                ?>
                            
                <div class="bloquestats">
                    <div id="respuestasglobales"></div>
                </div>

                <div class="frmadmin">
                    <select name="globalAsignatura" id="globalAsignatura" class="selAsignaturas"></select> 
                </div>
                <div class="clear"></div>

                

                <div class="clear"></div>
                <h2>Exámenes más fallados</h2>
                <div>
                    <ul class="listadmin">
                        <?
                        $bd3 = new BD();
                        $sqlMasFallos = "select v.id_resultado, v.id_usuario, v.titulo, v.id_examen, v.fecha, v.puntos, v.acertadas, v.fallidas, u.nombre, u.apellido, (((v.acertadas * 3)-v.fallidas)*100) / v.puntos as porcentaje from v_resultados_completo v inner join usuarios u on u.id_usuario = v.id_usuario inner join v_preguntas_temas_examenes pe on pe.id_examen = v.id_examen inner join temas t on t.id_tema = pe.id_tema where v.finalizado = 1 and v.puntos > 0 and u.nivel <> 1 and v.fecha >= ? and v.fecha <= ?";
                        $parametros = array($fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMD());
                        $entidad = "";
                        switch ($nivel) {
                            case 1:
                                $sqlMasFallos .= " and t.id_curso = ?";
                                array_push($parametros, $_REQUEST["id_curso"]);
                                $entidad = $_REQUEST["id_curso"];
                                break;
                            case 2:
                                $sqlMasFallos .= " and pe.id_tema = ?";
                                array_push($parametros, $ent[1]);
                                $entidad = $ent[1];
                                break;
                            case 3:
                                $sqlMasFallos .= " and pe.id_unidad = ?";
                                array_push($parametros, $ent[1]);
                                $entidad = $ent[1];
                                break;
                        }

                        $sqlMasFallos .= " group by v.id_resultado, v.id_usuario, v.titulo, v.id_examen, v.fecha, v.puntos, v.acertadas, v.fallidas, u.nombre, u.apellido order by porcentaje limit 20";
                        $bd3->setConsulta($sqlMasFallos);
                        $bd3->ejecutar($parametros);
                        while ($it = $bd3->resultado()) {
                            $puntuacion = number_format(((($it["acertadas"] * 3) - $it["fallidas"]) * 100) / $it["puntos"], 2, ",", "");
                            $porExamen = explode(",", $puntuacion);
                            ?>
                            <li class="peoresexamenes">
                                <a href="analizadorexamen.php?id_resultado=<?= $it["id_resultado"] ?>" target="_blank"><img src="css/icos/adminstats.png" /></a>
                                <span><?= $porExamen[0] ?><span>,<?= $porExamen[1] ?>%</span></span>
                                <?= $it["titulo"] ?> <br /> 
                                <?
                                $fecha = new Fecha($it["fecha"]);
                                echo $fecha->conFormatoSimpleConHora();
                                ?> 
                            <?= $it["nombre"] ?>, 
                            <?= $it["apellido"] ?>
                                <div class="clear"></div>
                            </li>
                        <? } ?>
                    </ul>
                </div>

                <h2>Preguntas con más aciertos</h2>
                <div class="mas-aciertos"></div>

                <h2>Preguntas con más fallos</h2>
                <div class="mas-fallos"></div>

                <h2>Preguntas más blancas</h2>
                <div class="mas-blancas"></div>
            </div>

            <div class="clear"></div>
        </div>


<? include 'trozos/pie.php'; ?>


        <script type="text/javascript">
            $(document).ready(function () {
                $.post('ajax/carga_mas_aciertos.php',
                        {
                            "entidad": <?= $entidad ?>,
                            "nivel": <?= $nivel ?>,
                            "fecha_inicio": <?= $_GET["fecha_inicio"] ?>,
                            "fecha_fin": <?= $_GET["fecha_fin"] ?>
                        }, function (respuesta) {
                        $('.mas-aciertos').html(respuesta);
                    }
                );

                $.post('ajax/carga_mas_fallos.php',
                        {
                            "entidad": <?= $entidad ?>,
                            "nivel": <?= $nivel ?>,
                            "fecha_inicio": <?= $_GET["fecha_inicio"] ?>,
                            "fecha_fin": <?= $_GET["fecha_fin"] ?>
                        }, function (respuesta) {
                        $('.mas-fallos').html(respuesta);
                    }
                );

                $.post('ajax/carga_mas_blancas.php',
                        {
                            "entidad": <?= $entidad ?>,
                            "nivel": <?= $nivel ?>,
                            "fecha_inicio": <?= $_GET["fecha_inicio"] ?>,
                            "fecha_fin": <?= $_GET["fecha_fin"] ?>
                        }, function (respuesta) {
                        $('.mas-blancas').html(respuesta);
                    }
                );
<? if (isset($_REQUEST["id_curso"])) { ?>
                    //LLeno los select Asignaturas con las asignaturas
                    var data = [
                    { text: '', value: '' },
    <?
    while ($it = $bd2->resultado()) {
        if (!isset($it["nombre"])) {
            ?>{text: '______<?= $it["nombreUnidad"] ?>', value: 'u_<?= $it["id_unidad"] ?>'}, <? } else {
            ?>{text: '<?= $it["nombre"] ?>', value: 't_<?= $it["id_tema"] ?>'},<?
        }
    }
    ?>];
                            var $select = document.getElementById("globalAsignatura");
                    $select.options.length = 0;
                    for (var i = 0; i < data.length; i++) {
                        var d = data[i];
                        $select.options.add(new Option(d.text, d.value))
                    }


                    //estadísticas de respuestas totales-----------------------------------------------------------------------------------------
                    //se pasa titulo serie y valor

                    var data = [['Acertadas (<?= $totalok ?>)', <?= $totalok ?>], ['Erradas (<?= $totalko ?>)', <?= $totalko ?>], ['Enblanco (<?= $totalnsnc ?>)', <?= $totalnsnc ?>]];
                    //se inicializa en objeto respuestasglobales es el id donde se renderiza el grafico
                    var plot1 = jQuery.jqplot('respuestasglobales', [data],
                            {
                                //se establecen los colores para las series
                                seriesColors: ['#7d9f64', '#b68080', '#ccc'],
                                seriesDefaults: {
                                    renderer: jQuery.jqplot.PieRenderer,
                                    rendererOptions: {showDataLabels: true, }
                                },
                                legend: {show: true, location: 'e'}
                            }
                    );
                    //estadísticas por asignatura------------------------------------------------------------------------------------º-----
                    $("#globalAsignatura").change(function () {
                        var idC = $(this).val();
                        var fechaIni = "";
                        var fechaFin = "";
                        if($('#fecha_inicio').val().length > 0){
                            fechaIni = "&fecha_inicio="+$('#fecha_inicio').val();
                        }
                        if($('#fecha_fin').val().length > 0){
                            fechaFin = "&fecha_fin="+$('#fecha_fin').val();
                        }
                        window.location.href = "globalstats.php?id_curso=<?= $_REQUEST["id_curso"] ?>&entidad=" + idC+fechaIni+fechaFin;
                    });
                });
<? } ?>
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
