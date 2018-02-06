<? include 'trozos/session.php';
require_once 'clases/Pregunta.php';
require_once 'clases/Paginacion.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <link rel="stylesheet" type="text/css" href="css/paginacion.css"/>
        <script type="text/javascript">
            function recarga(valor){
                $.post('ajax/listaPreguntasPerdidas.php', {'pag': valor}, function(respuesta){
                    $('#lista').slideToggle();
                    setTimeout(function(){
                        $('#lista').html(respuesta);
                        $(".colorfondo").each(function() {
                            var porcentaje = $(this).attr("data-color");
                            $(this).css('background-color', "#" + listaColores[porcentaje.split(".")[0]]);
                        });
                        $('#lista').slideToggle();
                    }, 500);
                });
            }
        </script>
    </head>
    <body>
        <?
        include 'trozos/encabezado.php';
        ?><div id="content">
            <div class="contplataforma">
                <h1>Preguntas Perdidas</h1>
                <a href="listcursos.php" class="botonoscuro">&lt;&lt; Atrás</a>
                <hr />
                <div class="clear"></div>
                <div id="lista">
                    <? include 'ajax/listaPreguntasPerdidas.php'; ?>
                </div>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>