<? include 'trozos/session.php';
require_once 'clases/Pregunta.php';
seguridad("todos");
$idResultado = 0;
if (isset($_GET["id_resultado"])) {
    $idResultado = $_GET["id_resultado"];
}
if (isset($_SESSION["id_resultado"])) {
    $idResultado = $_SESSION["id_resultado"] . "";
    $_SESSION["id_resultado"] = null;
}

$examen = Examen_BD::porIdResultado($idResultado);
if($_SESSION["usuario"]->getNivel() != 1){
    if($examen->getIdUsuario() != $_SESSION["usuario"]->getIdUsuario()){
        $_SESSION["aviso"] = "Acceso prohibido";
        header("Location: examenesrealizados.php");
    }
}
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
                <?              
                
                ?>
                <div id="resumencorrecionexamen">
                    <h2 class="tituloexamen">
                        <?=$examen->getTitulo()?>
                        <span><?=$examen->totalPreguntas()?> Preguntas</span> 
                        <div id="titulopuntuaciones">Puntuación máxima: <?=$examen->getPuntos()?> Puntos<br/>
                            <span>Respuesta correcta + 3</span><br/><span>Respuesta errada - 1</span><br/><span>Respuesta en blanco 0</span></div>
                    </h2>
                    <div align="right">Realizado el <? $fecha = new Fecha($examen->getFecha());
                        $fecha->conFormatoLargoConHora();?></div>
                    <div id="puntuacionporciento"><? $puntos = explode(",", $examen->porcentaje());
                        echo $puntos[0]; ?><span>,<?=$puntos[1]; ?>%</span>%</div>
                    <ul>
                        <li class="acertadas">Acertadas: <?=$examen->getAcertadas()?></li>
                        <li class="erradas">Erradas: <?=$examen->getFallidas()?></li>
                        <li class="blancas">Blancas: <?=$examen->blancas()?></li>
                        <li class="totalpuntos">Puntos: <?=$examen->puntuacion()?> de <?=$examen->getPuntos()?> puntos</li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <? $pregunta = new Pregunta(null, null, null, null, null, null, null,
                        null, null);
                $usuario = $_SESSION["usuario"];
                if (isset($_REQUEST["id_usuario"]) && isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
                    $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
                }
                $listaPreguntas = Pregunta_BD::porResultado($idResultado, $usuario->getIdUsuario());
                $cuentaPreguntas = 0;
                foreach ($listaPreguntas as $pregunta){
                    $cuentaPreguntas ++;?>
                    <div class="contexamenpregunta <?=$pregunta->clase()?>">
                        <div class="examenenunciado">
                            <span><?= $cuentaPreguntas?></span>
                            <? if($_SESSION["usuario"]->getNivel() == 1){?>
                                <b><?=$pregunta->getCodigo()?></b><br/>
                            <? } ?>
                            <?=$pregunta->imprimeEnunciado()?>
                        </div>
                        <div class="examenrecursos">
                            <? if($pregunta->getUrlVideo()!= null && $pregunta->getUrlVideo() != "") { ?>
                            <a href="<?= $pregunta->getUrlVideo() ?>" target="_blank"><?= $pregunta->getUrlVideo() ?></a>
                            <? } ?>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                            <div class="contexamenrespuestas">
	                        <? $respuesta = new Respuesta(null, null, null, null,
                                        null, null);
                                foreach ($pregunta->getListaRespuestas() as $respuesta){?>
                                	<div class="examenpregunta">
                                            <? if($respuesta->getIdRespuesta() == $respuesta->getMarcada()){ ?>
                                                <strong><?=$respuesta->imprimeTexto()?></strong>
                                            <? }else{
                                                echo $respuesta->imprimeTexto();
                                            }
                                            if ($respuesta->getCorrecta()){?>
                                                <img src="css/icos/correcionokmin.png" />
                                            <? }
                                            if($respuesta->getPosible()){ ?>
                                                <b>?</b>
                                            <? } ?>
                                        <br />
                                	</div>
                                <?  } ?>
                                <? if($pregunta->clase() == "ko" && $pregunta->getExplicacion() != null &&
                                        $pregunta->getExplicacion() != "" &&
                                        $_SESSION["usuario"]->getNivel() != 2 && $_SESSION["usuario"]->getNivel() != 3){ ?>
                                    <div class="examenexplicacion">
                                        <span>Explicación :</span>
                                        <?=$pregunta->imprimeExplicacion(); ?>
                                    </div>
                                <? }else{
                                    if($pregunta->getAnotacion()!= null && $pregunta->getAnotacion() != "" && $_SESSION["usuario"]->getNivel() != 2 && $_SESSION["usuario"]->getNivel() != 3){ ?>
                                    <div class="examenanotacion">
                                        <span>Recuerda :</span>
                                        <?= $pregunta->imprimeAnotacion()?>
                                    </div>
                                    <? }
                                } ?>
                            </div>                
                    </div>
                <? } ?>
            </div>
        </div>
        <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
