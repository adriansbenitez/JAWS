<? include 'trozos/session.php';
require_once 'clases/Examen.php';
require_once 'clases/Pregunta.php';
require_once 'clases/Respuesta.php';
seguridad("usuario");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/jquery.chrony.js?ver=1.1"></script>
        <script type="text/javascript" src="js/ajusteTiempo.js?ver=1.5"></script>
        <style type="text/css">
            #guardarycontinuar{
                border: 1px solid #7D3F00;
                display: inline-block;
                float: right;
                margin: 0 15px 0 auto;
                padding: 5px 10px;
                text-align: center;
                background: none repeat scroll 0 0 #FBF8E9;
                color: #000000;
                cursor: pointer;
            }
        </style>
        <? if($_SESSION["usuario"]->getIdUsuario() == 8){ ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('input[type=radio]').change(function(){
                    $('[name=pos_'+$(this).val()+']').click();
                });
                
                $('input[type=checkbox]').change(function(){
                    var marcables = parseInt($('[gru='+$(this).attr('gru')+']').length/2);
                    var marcadas = parseInt($('[gru='+$(this).attr('gru')+']:checked').length);
                    
                    if('pos_'+$('[name=opt'+$(this).attr('gru')+']:checked').val() == $(this).attr('name')){                        
                        $(this).attr('checked', 'checked');
                    }
                    
                    if(marcadas>marcables){
                        if('pos_'+$('[name=opt'+$(this).attr('gru')+']').val() != $(this).attr('name')){
                            $(this).removeAttr('checked');
                        }
                    }
                });
            });
        </script>
        <? } ?>
    </head>
    <body>
            <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <? $examen = new Examen(null, null, null, null, null);
            $examen = Examen_BD::examenARendir($_SESSION["usuario"]->getIdUsuario(), $_REQUEST["id_examen"]);
            $bd2 = new BD();
            $bd2->setConsulta("select rand(now()) azar, r.texto, r.id_respuesta, r.imagen, (select count(re.id_respuesta) from resultados_respuestas re where re.id_respuesta = r.id_respuesta and re.id_resultado = ?) marcada from respuestas r where r.id_pregunta = ? and r.activo = 1 order by azar"); ?>
            <div class="contplataforma">
                <h2 id="tituloexamen">
                    <?=$examen->getTitulo()?> 
                    <span><? echo $examen->totalPreguntas(); ?> Preguntas</span>
                    <div id="titulopuntuaciones">Puntuación máxima: <? echo $examen->getPuntos(); ?> Puntos  <span>Respuesta correcta + 3</span><span>Respuesta errada - 1</span> <span>Respuesta en blanco 0</span></div>
                </h2>
                <form method="post" action="funciones/controlador.php" id="frmexamen">
                    <div id="contdowncont">
                        <input type="hidden" name="restante" value="<?=$examen->elTiempo()?>"/>
                        <div id="timer"></div>
                        <div class="clear"></div>
                        <div id="guardarycontinuar">Guardar y continuar mas tarde</div>
                        <div class="clear"></div>
                        <input type="submit" value="Finalizar Examen" class="botonoscuro" />
                    </div>
                    <input type="hidden" name="accion" value="rendirExamen"/>
                    <input type="hidden" name="finalizado" value="1" id="esTemporal"/>
                    <input type="hidden" name="tiempo" id="tiempo" value="0" autocomplete="off" />
                    <input type="hidden" name="id_resultado" value="<?=$examen->getIdResultado()?>" />
                    <input type="hidden" value="<?=$examen->getIdExamen()?>" name="id_examen"/>                    

                    <? $listaPreguntas = Pregunta_BD::preguntasAzar($examen->getIdExamen());
                    $contadorpreguntas = 1;
                    $pregunta = new Pregunta(null, null, null, null, null, null, null, null, null);
                    foreach ($listaPreguntas as $pregunta) {
                        $marcada = false; ?>
                        <div class="contexamenpregunta">
                            <div class="examenenunciado">
                                <span><?= $contadorpreguntas;
                                    $contadorpreguntas++; ?></span>
                                <?=$pregunta->imprimeEnunciado()?>
                            </div>


                            <div class="examenrecursos">
                                <? if ($pregunta->getUrlVideo() != null) { ?>
                                    <a href="<?=$pregunta->getUrlVideo(); ?>" target="_blank">Ver Video</a>
                                <? } ?>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="contexamenrespuestas">
                                <div class="final">Respuesta Final</div>
                                <div class="posible">Posible Respuesta</div>
                                <div class="clear"></div>
                                <? $bd2->ejecutar(array($examen->getIdResultado(), $pregunta->getIdPregunta()));
                                $marcada = false;
                                while ($it = $bd2->resultado()) {
                                    $seleccionada = false;
                                    if ($it["marcada"] == 1) {
                                        $seleccionada = true;
                                        $marcada = true;
                                    } ?>
                                    <div class="examenpregunta">
                                        <input class="optrespuestas" type="radio" 
                                               name="opt<?=$pregunta->getIdPregunta()?>"
                                               value="<?=$it["id_respuesta"]?>"
                                        <?
                                        if ($seleccionada) {
                                            echo "checked";
                                        }
                                        ?>/>
                                        <?=str_replace("@i", '<img src="upload/pregunta/' . $it["imagen"] . '?ver='.  time().'"/>', $it["texto"]); ?>
                                        <input class="optrespuestas" type="checkbox" 
                                               name="pos_<?=$it["id_respuesta"]?>"
                                               gru="<?=$pregunta->getIdPregunta()?>"/>
                                    </div>
                                <? } ?>
                                <br />
                                <div class="examenpregunta">
                                    <input class="optrespuestas" type="radio" name="opt<?=$pregunta->getIdPregunta()?>" value="0" <? if (!$marcada) {
                                            echo "checked";
                                    } ?>/>
                                    Dejar en blanco 
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
