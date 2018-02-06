<?
include 'trozos/session.php';
include 'trozos/menuPerfil.php';
seguridad("usuario");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <link rel="stylesheet" type="text/css" href="js/dist/jquery.jqplot.min.css" />
        <style type="text/css">
            #tapa{
                opacity: 0.0;
                height: 100%;
                position: fixed;
                width: 100%;
                z-index: 20;
                background: #0f0f0f;
            }


            #tapa2 {
                height: 100%;
                position: fixed;
                width: 100%;
                z-index: 21;
                opacity: 0.0;
                cursor: wait;
            }

            #tapa2 #error{
                color: #0f0f0f;
                padding: 20px;
                text-shadow: 1px 1px 0 #CCCCCC;
                background: rgb(200, 200, 200);
                border-color: #593912;
                border-radius: 5px 5px 5px 5px;
                border-style: solid;
                border-width: 30px 1px 1px;
                box-shadow: 0 0 20px 0 #000000;
                font-family: Arial,Helvetica,sans-serif;
                margin: 20% auto 0;
                width: 400px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Estadísticas <img src="css/icos/icoperfil.png" alt="estadisticas"/></h1>
                <div class="colun2-3 perfilcolumn">
                    <div>
                        <?
                        $usuario = $_SESSION["usuario"];
                        if (isset($_REQUEST["id_usuario"]) && isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
                            $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
                        }
                        ?>
                        <?=menuPerfil($usuario)?>
                    </div>
                    <div>
                        <? $listaPeriodos = Periodo_BD::cargar($usuario->getIdUsuario());
                        $peri = 0;
                        if(count($listaPeriodos) > 0){
                            $periodo = new Periodo(null, null, null, null);
                            if(!empty($_GET["per"])){
                                $peri = $_GET["per"];
                            } ?>
                            <h2>¿Qué período deseas ver?</h2>
                            
                            <select name="periodo" id="select-periodo">
                                <option value="0">TODOS</option>
                                <? foreach($listaPeriodos as $periodo){?>
                                    <option value="<?=$periodo->getIdPeriodo()?>" <? if($peri == $periodo->getIdPeriodo()){ echo "selected";}?>><?=$periodo->getTitulo()?></option>
                                <? } ?>
                                <option value="-1" <? if($peri == -1){ echo "selected";}?>>ACTUAL</option>
                            </select>
                                    
                            
                        <? } ?>
                        
                        <h2>Resumen General Respuestas</h2>
                        <div class="bloquestats">
                            <div id="respuestasglobales"></div>
                        </div>

                        <h2>Estadisticas por asignaturas</h2>
                        <div class="frmadmin">
                            <select name="globalAsignatura" id="globalAsignatura" class="selAsignaturas"></select> 
                        </div>
                        <div class="clear15"></div>
                        <p id="asignatura">Elige una Asignatura para ver tus estadísticas</p>
                        <div class="clear"></div>
                        <div class="bloquestats">
                            <div id="respuestasporasignaturas"></div>
                        </div>
                        <div class="clear"></div>                
                        <div class="bloquestats">
                            <div id="respuestasporasignaturasevolucion"></div>
                        </div>

                        <h2>Ultimos exámenes realizados</h2>
                        <div class="bloquestats">
                            <div id="ultimosexamenes"></div>
                        </div>

                        <h2>Repeticiones exámenes</h2>
                        <div class="frmadmin">
                            <select name="repeticionExamenes" id="repeticionExamenes" > </select> 
                        </div>
                        <div class="clear15"></div>
                        <p id="repexamen">Elige un exámen repetido para ver su evolución</p>
                        <div class="bloquestats">
                            <div id="evolucionexamenesrepetidos"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>

        <? include 'trozos/pie.php'; ?>
        
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
        <? //valores para la primera grafica
        $totalok = 0;
        $totalko = 0;
        $totalnsnc = 0;

        $idUsuario = $usuario->getIdUsuario();
        
        $parametros = array();
        
        if($peri > 0){
                $bd = new BD();
                $bd->setConsulta("select p.fecha, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha < p.fecha order by pr.fecha desc limit 1) antes, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha > p.fecha) despues from periodos p where p.id_periodo = ? limit 1");
                $bd->ejecutar(array($idUsuario, $idUsuario, $peri));
                $it = $bd->resultado();
                
                $fecha = str_replace("-", "", $it["fecha"]);
                $despues = str_replace("-", "", $it["despues"]);
                $antes = str_replace("-", "", $it["antes"]);
            }else if($peri == -1){
                $bd = new BD();
                $bd->setConsulta("select MAX(fecha) as fecha from periodos where id_usuario = ?");
                $bd->ejecutar($idUsuario, $peri);
                $it = $bd->resultado();
                $fecha = str_replace("-", "", $it["fecha"]);
            }
            
            if($peri != 0){
                if($peri == -1){
                    $sql =" and r.fecha >= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues != null){
                    $sql =" and r.fecha >= ? and r.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $despues != null){
                    $sql =" and r.fecha <= ?";
                    array_push($parametros, $fecha);
                }else if($antes != null && $despues == null){
                    $sql .=" and r.fecha >= ? and r.fecha <= ?";
                    array_push($parametros, $antes);
                    array_push($parametros, $fecha);
                }else if($antes == null && $antes == null){
                    $sql =" and r.fecha <= ?";
                    array_push($parametros, $fecha);
                }
            }
        
        $param = array();
        
        $sqlGraf1 = "select (select count(rrr.correcta) from resultados r inner join resultados_respuestas rr on rr.id_resultado = r.id_resultado inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where r.id_usuario = ? and rrr.correcta = 1";
        array_push($param, $idUsuario);
        if($peri != 0){
            $sqlGraf1 .= $sql;
            foreach ($parametros as $pa){
                array_push($param, $pa);
            }
        }
        $sqlGraf1 .= ") correctas,";
        
        $sqlGraf1 .= "(select count(rrr.correcta) from resultados r inner join resultados_respuestas rr on rr.id_resultado = r.id_resultado inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where r.id_usuario = ? and rrr.correcta = 0";
        array_push($param, $idUsuario);
        if($peri != 0){
            $sqlGraf1 .= $sql;
            foreach ($parametros as $pa){
                array_push($param, $pa);
            }
        }
        $sqlGraf1 .= ") erradas,";
        
        $sqlGraf1 .= "(select count(ep.id_pregunta) from examenes e inner join examenes_preguntas ep on ep.id_examen = e.id_examen inner join resultados r on r.id_examen = e.id_examen where r.id_usuario = ?";
        array_push($param, $idUsuario);
        if($peri != 0){
            $sqlGraf1 .= $sql;
            foreach ($parametros as $pa){
                array_push($param, $pa);
            }
        }
        
        $sqlGraf1 .=") total";

        $bd = new BD();
        
        $bd->setConsulta($sqlGraf1);
        
        
        $bd->ejecutar($param);
        
        if($it = $bd->resultado()){
            $totalok = $it["correctas"];
            $totalko = $it["erradas"];
            $totalnsnc= $it["total"] - $it["erradas"] - $it["correctas"];
        }
        
        //valores para la segunda grafica
        $param2 = array();
        
        if($_SESSION["usuario"]->getNivel() == 1){
            $sqlGraf2 = "select t.id_tema, concat(c.nombre, '-', t.nombre) nombre, null id_unidad, null nombreUnidad from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join respuestas r on r.id_pregunta = p.id_pregunta inner join resultados_respuestas rr on rr.id_respuesta = r.id_respuesta inner join resultados rrr on rrr.id_resultado = rr.id_resultado inner join cursos c on c.id_curso = t.id_curso where rrr.id_usuario = ? group by t.id_tema, t.nombre union select t.id_tema, null, u.id_unidad, u.nombre nombreUnidad from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join respuestas r on r.id_pregunta = p.id_pregunta inner join resultados_respuestas rr on rr.id_respuesta = r.id_respuesta inner join resultados rrr on rrr.id_resultado = rr.id_resultado where rrr.id_usuario = ? order by id_tema, nombre desc, nombreUnidad";
        }else{
            $sqlGraf2 = "select t.id_tema, t.nombre nombre, null id_unidad, null nombreUnidad from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join respuestas r on r.id_pregunta = p.id_pregunta inner join resultados_respuestas rr on rr.id_respuesta = r.id_respuesta inner join resultados rrr on rrr.id_resultado = rr.id_resultado where rrr.id_usuario = ? group by t.id_tema, t.nombre union select t.id_tema, null, u.id_unidad, u.nombre nombreUnidad from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join respuestas r on r.id_pregunta = p.id_pregunta inner join resultados_respuestas rr on rr.id_respuesta = r.id_respuesta inner join resultados rrr on rrr.id_resultado = rr.id_resultado where rrr.id_usuario = ? order by id_tema, nombre desc, nombreUnidad";
        }
        
        array_push($param2, $idUsuario);
        array_push($param2, $idUsuario);
        
        $bd->setConsulta($sqlGraf2);
        
        
        $bd->ejecutar($param2);
        
        $listaGrafica2 = array();
        
        while($it = $bd->resultado()){
            array_push($listaGrafica2, $it);
        }
        
        //datos para la tercera grafica
        $sqlGraf3 = "select r.id_resultado, r.fecha, (select count(rrr.correcta) from resultados r1 inner join resultados_respuestas rr on rr.id_resultado = r1.id_resultado" .
" inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where rrr.correcta = 1 and r1.id_resultado = r.id_resultado) correctas," .
" (select count(rrr.correcta) from resultados r1 inner join resultados_respuestas rr on rr.id_resultado = r1.id_resultado" .
" inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where rrr.correcta = 0 and r1.id_resultado = r.id_resultado) incorrectas," .
" (select count(ep1.id_pregunta) from examenes_preguntas ep1 where ep1.id_examen = ep.id_examen) total_preguntas from resultados r inner join examenes_preguntas ep on ep.id_examen = r.id_examen where r.id_usuario = ?";
        $param3 = array($usuario->getIdUsuario());
        if($peri != 0){
            $sqlGraf3 .= $sql;
            foreach ($parametros as $pa){
                array_push($param3, $pa);
            }
        }
        
        $sqlGraf3 .= " group by r.id_resultado, r.fecha".
        " order by fecha limit 5";
        
        $bd->setConsulta($sqlGraf3);
        
        $bd->ejecutar($param3);
        
        $notas = "";
        $fechas = "";
        
        $cont2 = 0;
        
        while($it = $bd->resultado()){
            $cont2++;
            if($it["total_preguntas"] == 0){
                $notas.= ",0";
            }else{
                $notas.= ",". number_format(((($it["correctas"]*3)-$it["incorrectas"])*100)/($it["total_preguntas"]*3), 2, ".", "");
            }
            $fechaTemp = explode("-", substr($it["fecha"], 0, 10));
            $fechas.=",'".$fechaTemp[2]."/".$fechaTemp[1]."/".$fechaTemp[0]."'";
        }
        
        if($cont2 > 0){
            $notas = substr($notas, 1);
            $fechas = substr($fechas, 1);
        }
        
        //datos para la cuarta gráfica
        $sqlGraf4 = "select r.titulo, r.id_examen
            from v_resultados_completo r
            where r.id_usuario = ?";
        $param4 = array($idUsuario);
        if($peri != 0){
            $sqlGraf4 .= $sql;
            foreach ($parametros as $pa){
                array_push($param4, $pa);
            }
        }
        $sqlGraf4 .= " group by titulo, id_examen";
        
        $bd->setConsulta($sqlGraf4);
        
        $bd->ejecutar($param4);
        
        $listaGrafica4 = array();
        
        while($it = $bd->resultado()){
            array_push($listaGrafica4, $it);
        } ?>
        
        <script type="text/javascript">
            
            $('#select-periodo').change(function(){
                var url = "<? 
                $cad = str_replace("?per", "&per", $_SERVER["REQUEST_URI"]);
                $url = explode("&", $cad);
                
                echo $url[0]; ?>";
                var valor = "";
                if($(this).val() != "TODOS"){
                    valor = $(this).val();
                }
                
                var parametro = "?";
                
                if(url.lastIndexOf("?") != -1){
                    parametro = "&";
                }
                
                window.location = url+parametro+"per="+valor;
            });



            $(document).ready(function(){
                
            //LLeno los select Asignaturas con las asignaturas
            var data = [
            { text: '', value: '' },
                    <? foreach ($listaGrafica2 as $it){                    
                    if(!isset($it["nombre"])){ ?>
                    
            { text: "______" + '<?=mb_ereg_replace("'", "\\'",$it["nombreUnidad"]) ?>', value: 'u<?=$it["id_unidad"]?>' },
                    <? }else{ ?>
            { text: '<?=mb_ereg_replace("'", "\\'",$it["nombre"])?>', value: 't<?=$it["id_tema"]?>' },
                    <? } 
                    } ?>
            ];
            var $select = document.getElementById("globalAsignatura");
            $select.options.length = 0;
            for (var i = 0; i < data.length; i++){
                var d = data[i];
                $select.options.add(new Option(d.text, d.value))
            }

            //Lleno los select de examen repeditos
            var data2 = [
            { text: '', value: '' },
                <? foreach($listaGrafica4 as $it){ ?>
                    { text: '<?=mb_ereg_replace("'", "\\'",$it["titulo"]) ?>', value: '<?=$it["id_examen"]?>' },
                <? } ?>
            ];
            var $select = document.getElementById("repeticionExamenes");
            $select.options.length = 0;
            for (var i = 0; i < data2.length; i++){
                var d = data2[i];
                $select.options.add(new Option(d.text, d.value))
            }

            //estadísticas de respuestas totales-----------------------------------------------------------------------------------------
            //se pasa titulo serie y valor
            var data = [['Acertadas (<? echo $totalok; ?>)', <? echo $totalok; ?> ], ['Erradas (<? echo $totalko; ?>)', <? echo $totalko; ?> ], ['Enblanco (<? echo $totalnsnc; ?>)', <? echo $totalnsnc; ?> ]];
                    //se inicializa en objeto respuestasglobales es el id donde se renderiza el grafico
                    var plot1 = jQuery.jqplot ('respuestasglobales', [data],
                    {
                    //se establecen los colores para las series
                    seriesColors:['#7d9f64', '#b68080', '#ccc'],
                            seriesDefaults: {
                            renderer: jQuery.jqplot.PieRenderer,
                                    rendererOptions: {showDataLabels: true, }
                            },
                            legend: { show:true, location: 'e' }
                    }
                    );

            //estadísticas por asignatura-----------------------------------------------------------------------------------------
            $("#globalAsignatura").change(function(){
            var idC = $(this).val();
            $("#asignatura").html($("#globalAsignatura option[value='" + idC + "']").text())
                    var plot3
                    var plot4
                    $('body').prepend('<div id="tapa">' +
                    '</div>' +
                    '<div id="tapa2">' +
                    '<div id="error">' +
                    '<b>Elaborando estadistica</b>' +
                    '<div class="clear"></div>' +
                    '<div style="height: 30px; margin: 10px;"><img alt="cargando"' +
                    'src="images/progress_bar_barra.gif" /></div>' +
                    '</div>' +
                    '</div>');
                    $('#tapa').animate({opacity: 0.5}, 300, function(){
            });
                    $('#tapa2').animate({opacity: 1}, 300, function(){
            });
            $.get("ajax/globalAsignatura.php?id_usuario=<? echo $usuario->getIdUsuario(); ?>&idc=" + idC, function(esdata){

                //global
                //var data2 = [['Acertadas (<%=totalok%>)', <%=totalok%>],['Erradas (<%=totalko%>)', <%=totalko%>], ['Enblanco (<%=totalnsnc%>)', <%=totalnsnc%>]];
                var data = eval(esdata)

                //console.log(plot3)					
                //			if (plot3!=undefined){
                //			plot3.destroy();
                //			}

                $("#respuestasporasignaturas").html("");
                plot3 = jQuery.jqplot ('respuestasporasignaturas', [data],
                {
                    //se establecen los colores para las series
                    seriesColors:['#7d9f64', '#b68080', '#ccc'],
                    seriesDefaults: {
                                    renderer: jQuery.jqplot.PieRenderer,
                                            rendererOptions: {showDataLabels: true, }
                                    },
                                    legend: { show:true, location: 'e' }
                            });
                            setTimeout(function(){
                            $('#tapa').animate({opacity: 0}, 500, function(){
                            $('#tapa').remove();
                            });
                                    $('#tapa2').animate({opacity: 0}, 500, function(){
                            $('#tapa2').remove();
                            });
                            }, 1200);
                    });
                    $.get("ajax/evolucionAsignatura.php?id_usuario=<? echo $usuario->getIdUsuario(); ?>&idc=" + idC, function(esdata){

                    eval(esdata) //crea un object llamado resp			
                            var data = resp

                            $("#respuestasporasignaturasevolucion").html("");
                            //evolucion
                            var line1 = data.notas;
                            var line2 = data.notasfalladas;
                            var line3 = data.notasblanco;
                            var ticks = data.fechas;
                            plot4 = $.jqplot('respuestasporasignaturasevolucion', [line1, line2, line3], {
                            seriesColors:['#7d9f64', '#b68080', '#ccc'],
                                    axes:{
                                    xaxis:{
                                    ticks: ticks,
                                            renderer: $.jqplot.CategoryAxisRenderer,
                                            labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                                            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                            tickOptions: {fontSize: '8pt'}
                                    },
                                    },
                                    highlighter: {
                                    show: true,
                                            sizeAdjust: 7.5,
                                            tooltipFormatString: '%s %',
                                            tooltipAxes: 'y'
                                    },
                            });
                    });
            });
                    $("#repeticionExamenes").change(function(){
            var idC = $(this).val();
                    $("#asignatura").html($("#globalAsignatura option[value='" + idC + "']").text())

                    var plot3
                    var plot4


                    $.get("ajax/repeticionExamenes.php?id_usuario=<? echo $usuario->getIdUsuario(); ?>&id_examen=" + idC, function(esdata){

                    eval(esdata) //crea un object llamado resp			
                    var data = resp

                            $("#evolucionexamenesrepetidos").html("");
                            //evolucion
                            var line1 = data.notas;
                            var line2 = data.notasfalladas;
                            var line3 = data.notasblanco;
                            var ticks = data.fechas;
                            plot4 = $.jqplot('evolucionexamenesrepetidos', [line1, line2, line3], {
                            seriesColors:['#7d9f64', '#b68080', '#ccc'],
                                    axes:{
                                    xaxis:{
                                    ticks: ticks,
                                            renderer: $.jqplot.CategoryAxisRenderer,
                                            labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                                            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                            tickOptions: {fontSize: '8pt'}
                                    },
                                    },
                                    highlighter: {
                                    show: true,
                                            sizeAdjust: 7.5,
                                            tooltipFormatString: '%s %',
                                            tooltipAxes: 'y'
                                    },
                            });
                    });
            })

                    /*Ultimos examenes*/
                    var line1 = [ <? echo $notas; ?> ];
                    var ticks = [ <? echo $fechas; ?> ];
                    var plot1 = $.jqplot('ultimosexamenes', [line1], {
                    seriesColors:['#7d9f64', '#b68080', '#ccc'],
                            axes:{
                            xaxis:{
                            ticks: ticks,
                                    renderer: $.jqplot.CategoryAxisRenderer,
                                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                                    tickOptions: {fontSize: '8pt'}
                            },
                            },
                            highlighter: {
                            show: true,
                                    sizeAdjust: 7.5,
                                    tooltipFormatString: '%s %',
                                    tooltipAxes: 'y'

                            },
                    });
            });
        </script>  
    </body>
</html>
