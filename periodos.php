<?
include 'trozos/session.php';
include 'trozos/menuPerfil.php';
seguridad("usuario");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
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
                <h1>Periodos <img src="css/icos/admincalendario.png" alt="Periodos"/></h1>
                <div class="colun2-3 perfilcolumn">
                    <div>
                        <?
                        $usuario = $_SESSION["usuario"];
                        if (isset($_REQUEST["id_usuario"]) && isset($_SESSION["usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
                            $usuario = Usuario_BD::porId($_REQUEST["id_usuario"]);
                        }
                        ?>
                        <? echo menuPerfil($usuario); ?>
                    </div>
                    <div>
                        <?
                        $periodo = new Periodo(null, null, null, null);
                        $listaPeriodos = Periodo_BD::cargar($usuario->getIdUsuario());
                        if (count($listaPeriodos) > 0) {
                            ?>
                            <h2>Los períodos:</h2>
                            <ul class="listadmin">
                            <? foreach ($listaPeriodos as $periodo) { ?>
                                <li><?= $periodo->getTitulo() ?> <br/>
                                    <b>Fecha: </b><?= $periodo->getFecha()->conFormatoSimple() ?>
                                    <a href="funciones/controlador.php?accion=borrar_periodo&id_periodo=<?= $periodo->getIdPeriodo(); ?>"><img src="css/icos/admindelete.png" alt="borrar"/></a>
                                </li>
                            <? } ?>
                            </ul>
                        <? } else { ?>
                            <h2>No hay períodos</h2>
<? } ?>
                        <h2>Nuevo período:</h2>
                        <form action="funciones/controlador.php" method="post" style="width: 80%; margin: 0 auto;">
                            <input type="hidden" name="accion" value="alta_periodo"/>
                            <input type="hidden" name="id_usuario" value="<?= $usuario->getIdUsuario() ?>"/>
                            <div class="izquierda">Título:</div>
                            <div class="derecha">
                                <input type="text" name="titulo"/>
                            </div>
                            <div class="clear" style="height: 30px;"></div>

                            <div class="izquierda">Fecha:</div>
                            <div class="derecha">
                                <input type="text" name="fecha" class="fecha"/>
                            </div>
                            <div class="clear" style="height: 20px;"></div>

                            <input type="submit" value="Alta" class="botonoscuro derecha"/>
                        </form>
                    </div>
                </div>
            </div>

            <div class="clear" style="height: 240px;"></div>
        </div>

        <? include 'trozos/pie.php'; ?>
        <script type="text/javascript">
            $(document).ready(function(){
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
            });
        </script>
    </body>
</html>
