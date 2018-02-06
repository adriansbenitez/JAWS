<? include 'trozos/session.php';
require_once 'clases/Examen.php';
require_once 'clases/Paginacion.php';
require_once 'clases/Periodo.php';

seguridad("usuario");

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <link type="text/css" rel="stylesheet" href="css/paginacion.css"/>
        <style type="text/css">
            #busca{
                cursor: pointer;
            }
        </style>
        <script type="text/javascript">
            <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
            $(document).ready(function(){
                $('#busca').click(function(){
                    recarga(1);
                });
            });
                
            function recarga(valor){
                $.post('ajax/listaExamenes.php', {'pag': valor, 'filtro': $('#campoBusqueda').val()}, function(respuesta){
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
            <? }else{ ?>
            $(document).ready(function(){
                $('[name=periodo_elegido]').change(function(){
                    $('#periodo').val($(this).val());
                    recarga(1);
                });
            });
                
            function recarga(valor){
                $.post('ajax/listaExamenes.php', {
                        'pag': valor,
                        'periodo': $('#periodo').val()
                    }, function(respuesta){
                        $('#lista').slideToggle();
                        setTimeout(function(){
                            $('#lista').html(respuesta);
                            $(".colorfondo").each(function() {
                                var porcentaje = $(this).attr("data-color");
                                $(this).css('background-color', "#" + listaColores[porcentaje.split(".")[0]]);
                            });
                            $('#lista').slideToggle();
                        }, 500);
                    }
                );
            }
            <? } ?>
        </script>
    </head>
    <body>
        <input type="hidden" id="periodo" value="0"/>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">    
            <div class="contplataforma">
                <h1>Exámenes <img src="css/icos/icoexamenes.png" /></h1>
                <a href="addexamen.php" class="botonoscuro">+ Crear Nuevo Examen</a>
                <? if ($_SESSION["usuario"]->getNivel() == 1) { ?>
                    <a href="addexamenPersonalizado.php" class="botonoscuro">+ Crear Nuevo Examen Personalizado</a>
                    <span style="float: right;">Buscar: <input type="text" id="campoBusqueda"/> <img src="css/icos/admindetalles.png" id="busca"/></span>
                <? } ?>
                <? if ($_SESSION["usuario"]->getNivel() > 2 && $_SESSION["usuario"]->getMasFalladas() == 1) { ?>
                    <a href="add_examen_falladas.php" class="botonoscuro">+ Examen con las preguntas más falladas</a>
                    <span style="float: right;">Buscar: <input type="text" id="campoBusqueda"/> <img src="css/icos/admindetalles.png" id="busca"/></span>
                <? } ?>
                <div class="clear"></div>
                <? $periodos = array();
                    if($_SESSION["usuario"]->getNivel() >= 3){
                        $periodos = Periodo_BD::cargar($_SESSION["usuario"]->getIdUsuario());
                    }

                if(count($periodos) > 0){ ?>
                <div class="periodos">
                    Períodos: 
                    <select name="periodo_elegido">
                        <option value="0">Todos</option>
                        <? foreach ($periodos as $periodo){ ?>
                        <option value="<?=$periodo->getIdPeriodo()?>"><?=$periodo->getTitulo()?></option>
                        <? } ?>
                        <option value="-1">Actual</option>
                    </select>
                </div>
                <div class="clear"></div>
                <? } ?> 
                <div id="lista">
                    <? include 'ajax/listaExamenes.php'; ?>
                </div>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
