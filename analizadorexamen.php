<? include 'trozos/session.php';
require_once 'clases/BD.php';
require_once 'clases/Pregunta.php';
require_once 'clases/Unidad.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
                #examenAnalisis ul, #examenAnalisis ul li{
                    list-style: none;
                    margin: 10px 0px;
                    padding: 0;
                    text-decoration: none;
                }

                #examenAnalisis ul ul, #examenAnalisis ul ul li{
                    list-style: square;
                    margin: 10px 0 10px 30px;
                    padding: 0;
                    text-decoration: none;
                }

                #examenAnalisis ul ul ul, #examenAnalisis ul ul ul li{
                    list-style: circle;
                    margin: 10px 0 10px 30px;
                    padding: 0;
                    text-decoration: none;
                }

                #examenAnalisis ul ul ul ul, #examenAnalisis ul ul ul ul li{
                    list-style: disc;
                    margin: 10px 0 10px 30px;
                    padding: 0;
                    text-decoration: none;
                }


                #examenAnalisis ol, #examenAnalisis ol li{
                    margin: 10px 0 10px 40px;
                    padding: 0;
                    text-decoration: none;
                    list-style-type:decimal !important;
                }

                .alumnos{
                    float: right;
                    background: #5c3c15;
                    width: 160px;
                    height: 50px;
                    padding: 10px 20px;
                    border-radius: 15px;
                    box-shadow: 2px 2px 5px #000000;
                    text-align: center;
                }

                .alumnos .listaAlumnos{
                    top: 20px;
                    display: none;
                    text-align: left;
                }

                .alumnos .listaAlumnos .alumno{
                    background: none repeat scroll 0 0 #F8F5E3;
                    border: 1px solid #000000;
                    border-radius: 10px 10px 10px 10px;
                    height: 23px;
                    left: -160px;
                    margin: 10px 0 10px 10px;
                    padding: 2px 10px;
                    position: relative;
                    width: 430px;
                }
                
                .alumnos .listaAlumnos .alumno > a{
                    float: right;
                    margin-left: 5px;
                }

                .alumnos .listaAlumnos .alumno:hover{
                    color: #f8f5e3;
                    background: #aaaaaa;
                }


                .alumnos .listaAlumnos .alumno div{
                    position: relative;
                }

                .alumnos .listaAlumnos .alumno div:hover{
                    color: #f8f5e3;
                    background: #aaaaaa;
                }

                #examenAnalisis .correcta{
                    color: #009900;
                }

                #examenAnalisis .incorrecta{
                    color: #990000;
                }

                #content{
                    background: #F8F5E6;
                }
            </style>
            <script type="text/javascript">
            $(document).ready(function() {
                $('#selectgrupoUsuarios').change(function() {
                    document.location.href = "analizadorexamen.php?idexamen=<? echo $_REQUEST["id_examen"]; ?>&idusuario=" + $(this).val();
                });
                $('#verResultados').attr("estado", 0);
                $('#verResultados').click(function() {
                    if($('#verResultados').attr("estado") == 0){
                        $.post("ajax/resultadosPorFechas.php", 
                            {"id_examen": <?=$_GET["id_examen"]?>},
                            function(respuesta){
                                $('.listaAlumnos').html(respuesta);
                                setTimeout(function(){
                                    $('.listaAlumnos').slideDown();
                                }, 200);
                            }
                        );
                        $('#verResultados').attr("estado", 1);
                    }else{
                        $('.listaAlumnos').slideUp();
                        $('#verResultados').attr("estado", 0);
                    }
                });
            });
        </script>
    </head>
    <body>
<? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <? $examen = new Examen(null, null, null, null, null);
            if (isset($_REQUEST["id_resultado"])) {
                $examen = Examen_BD::porIdResultado($_REQUEST["id_resultado"]);
            } else {
                $examen = Examen_BD::porId($_REQUEST["id_examen"]);
            } ?>
            <div class="contplataforma">
                <h2 id="tituloexamen"><?=$examen->getTitulo()?>  <img src="css/icos/icoexamenes.png" />
                    <span><? echo $examen->totalPreguntas(); ?> Preguntas</span>
                    <div class="titulopuntuaciones">Puntuación máxima: <?=$examen->getPuntos()?> Puntos  <span>Respuesta correcta + 3</span><span>Respuesta errada - 1</span> <span>Respuesta en blanco 0</span>
                    </div>
                    <? if (isset($_REQUEST["id_resultado"])) { ?>
                        <div class="titulopuntuaciones">Puntuacion obtenida: <?=$examen->puntuacion()?> Puntos 
                            <span>Correctas: <?=$examen->getAcertadas()?></span> 
                            <span>Incorrectas: <?=$examen->getFallidas()?></span>
                            <span>En Blanco: <?=$examen->blancas()?></span>
                        </div>
                    <? } ?>
                </h2>

                <div class="clear15"></div>

                <div class="alumnos">
                    <input type="button" value="Ver Resultados" id="verResultados" class="botonoscuro"/>
                    <div class="listaAlumnos">
                    </div>
                </div>
                    
                    <div id="examenAnalisis"><?
                        $bd = new BD();
                        $compCurso = 0;
                        $compTema = 0;
                        $compUnidad = 0;
                        $compAnyo = 0;

                        $sql = "";
                        $parametros = null;
                        if (isset($_REQUEST["id_resultado"])) {
                            $sql = "select ep.id_examen, c.id_curso, c.nombre nombreCurso, t.id_tema, t.nombre nombreTema, u.id_unidad, u.nombre nombreUnidad, a.id_anyo, a.nombre nombreAnyo, p.codigo, p.enunciado, (select res.texto from respuestas res inner join resultados_respuestas rr on rr.id_respuesta = res.id_respuesta where res.id_pregunta = p.id_pregunta and rr.id_resultado = ?) texto, p.id_pregunta, (select res.correcta from respuestas res inner join resultados_respuestas rr on rr.id_respuesta = res.id_respuesta where res.id_pregunta = p.id_pregunta and rr.id_resultado = ?) correcta from preguntas p inner join examenes_preguntas ep on ep.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ep.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso where ep.id_examen = ? order by id_curso, id_tema, id_unidad, id_anyo, codigo";
                            $parametros = array($examen->getIdResultado(), $examen->getIdResultado(), $examen->getIdExamen());
                        } else {
                            $sql = "select ep.id_examen, c.id_curso, c.nombre nombreCurso, t.id_tema, t.nombre nombreTema, u.id_unidad, u.nombre nombreUnidad, a.id_anyo, a.nombre nombreAnyo, p.codigo, p.enunciado, p.id_pregunta from preguntas p inner join examenes_preguntas ep on ep.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ep.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join cursos c on c.id_curso = t.id_curso where ep.id_examen = ? order by id_curso, id_tema, id_unidad, id_anyo, codigo";
                            $parametros = $examen->getIdExamen();
                        }

                        $bd->setConsulta($sql);
                        $bd->ejecutar($parametros);
                        while ($it = $bd->resultado()) {
                            if ($compTema != $it["id_tema"]) {
                                if ($compTema != 0) {
                                    echo "</ul>";
                                }
                            }
                            
                            if ($compUnidad != $it["id_unidad"]) {
                                if ($compUnidad != 0) {
                                    echo "</ul>";
                                }
                            }
                            
                            if ($compAnyo != $it["id_anyo"]) {
                                if ($compAnyo != 0) {
                                    echo "</ol>";
                                }
                            }
                            
                            if ($compCurso != $it["id_curso"]) {
                                $compCurso = $it["id_curso"]; ?>
                                <ul class="cuenta_curso c_curso_<?=$it["id_curso"]?>">
                                    <li>Curso <?=$it["nombreCurso"]?><br/>
                                        <span class="v_curso_<?=$it["id_curso"]?>"></span> Preguntas</li>
                                    <ul>
                            <? }
                                    if ($compTema != $it["id_tema"]) {
                                        $compTema = $it["id_tema"];
                                        ?><li>Tema <?=$it["nombreTema"]?><br/>
                                            <span class="v_tema_<?=$it["id_tema"]?>"></span> preguntas</li><ul class="cuenta_tema c_tema_<?=$it["id_tema"]?>">
                                        <?
                                        }
                                        if ($compUnidad != $it["id_unidad"]) {
                                            $compUnidad = $it["id_unidad"];
                                            ?><li>Unidad <?=$it["nombreUnidad"]?><br/>
                                                <span class="v_unidad_<?=$it["id_unidad"]?>"></span> preguntas</li><ul class="cuenta_unidad c_unidad_<?=$it["id_unidad"]?>"><?
                                            }
                                            if ($compAnyo != $it["id_anyo"]) {
                                                $compAnyo = $it["id_anyo"];
                                                ?><li>Año <?=$it["nombreAnyo"]?><br/>
                                                  </li><ol type = "1"><?
                                                }

                                                if (isset($_REQUEST["id_resultado"])) {
                                                    $clase = "";
                                                    $respuesta = "";
                                                    if ($it["correcta"] != null) {
                                                        $respuesta = $it["texto"];
                                                        if ($it["correcta"]) {
                                                            $clase = "correcta";
                                                        } else {
                                                            $clase = "incorrecta";
                                                        }
                                                    }
                                                    ?><li class="pregunta <?=$clase?>">
                                                        <? $pregunta = Pregunta_BD::porId($it["id_pregunta"]);
                                                        echo $pregunta->porcentajeConFormato(); ?> - <?=$it["codigo"]?><br/>
                                                        <? echo $it["enunciado"]; ?>
                                                            <? if ($respuesta != "") { ?>
                                                            <ul><li style="list-style:none !important; color: #000000 !important;"><b><?=$respuesta?></b></li></ul>
                                                            <? } ?>
                                                        <? } else { ?>
                                                            <li class="pregunta">
                                                                <? $pregunta = Pregunta_BD::porId($it["id_pregunta"]);
                                                                echo $pregunta->porcentajeConFormato(); ?> - <?=$it["codigo"]?><br/>
                                                                <?=$it["enunciado"]?></li>
                                                        <? }
                                                        $unidad = Unidad_BD::porVirtual($pregunta->getIdPregunta());
                                                        if(isset($unidad)){ ?>
                                                            <br/><b><?=$unidad->getNombre()?></b>
                                                        <? } ?>
                                                    </li>
                                                <? } ?>
                                        </ol></ul></ul>	
                                </ol>
                            </ul>
                    </div>
                </div>

                <div class = "clear"></div>
            </div>

    <? include 'trozos/pie.php'; ?>
    <script type="text/javascript">
        $('.cuenta_curso').each(function(ind, ele){
            var total = $(ele).find('.pregunta').length;
            var clase = $(ele).attr('class').replace('cuenta_curso', '').replace(' ', '').replace('c_', 'v_');
            $('.'+clase).html(total);
        });
        
        $('.cuenta_tema').each(function(ind, ele){
            var total = $(ele).find('.pregunta').length;
            var clase = $(ele).attr('class').replace('cuenta_tema', '').replace(' ', '').replace('c_', 'v_');
            $('.'+clase).html(total);
        });
        
        $('.cuenta_unidad').each(function(ind, ele){
            var total = $(ele).find('.pregunta').length;
            var clase = $(ele).attr('class').replace('cuenta_unidad', '').replace(' ', '').replace('c_', 'v_');
            $('.'+clase).html(total);
        });
    </script>
    </body>
</html>