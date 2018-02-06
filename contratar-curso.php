<? include 'trozos/session.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Contratar - Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
            .bloquecontratar{
                padding: 20px 25px;
                display: table;
                width:1px;
                height: 1px;
            }
        </style>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="texto">
                <h2>Contratar</h2>
                <form id="frmcontacto" method="post" action="funciones/controlador.php">
                    <input type="hidden" name="accion" value="contratar"/>
                    <? if (isset($_REQUEST["id"]) && $_REQUEST["id"] == 2) { ?>
                        <input type="hidden" name="p_manual" value="Si"/>
                    <? } ?>
                    <? if (isset($_REQUEST["tarifa"])) { ?>
                        <input type="hidden" name="tarifa" value="<?= $_REQUEST["tarifa"] ?>"/>
                    <? } ?>
                    <input type="hidden" name="tipo" value="<? if (isset($_REQUEST["tarifa"]) && $_REQUEST["tarifa"] == "1") {
                        echo "Personal Training";
                    } else {
                        echo "Training Premium";
                    }
                    ?>"/>
                    <? if (isset($_SESSION["usuario"])) {
                        $usuario = $_SESSION["usuario"];
                        ?>
                        <fieldset>Nombre</fieldset>
                        <input type="text" name="nombre" disabled value="<?= $usuario->getNombre() ?>" />

                        <fieldset>Apellidos</fieldset>
                        <input type="text" name="apellidos" disabled value="<?= $usuario->getApellido() ?>"/>

                    <? } else { ?>
                        <fieldset>Email *</fieldset>
                        <input type="text" name="email" class="requerido"/>

                        <fieldset>Clave *</fieldset>
                        <input type="password" name="clave" style="padding: 5px; width: 93%;" class="requerido"/>

                        <fieldset>Nombre *</fieldset>
                        <input type="text" name="nombre" class="requerido"/>

                        <fieldset>Apellidos *</fieldset>
                        <input type="text" name="apellidos" class="requerido"/>

                    <? } ?>
                    <? if(isset($_REQUEST["id_curso"])){?>
                        <input type="hidden" name="interes" value="<?= $_GET["id_curso"] ?>"/>
                    <? }else{ ?>
                        <fieldset>Interesado en*</fieldset>
                        <select name="interes" class="requerido">
                            <option value="0">¿Qué curso te interesa?</option>
                            <option value="1">Curso BIR</option>
                            <option value="2">Curso QIR</option>
    <? /* <option value="7">Curso FIR</option> */ ?>
                        </select>
                    <? } ?>
                    <fieldset>Dirección *</fieldset>
                    <input type="text" name="direccion" class="requerido"/>

                    <fieldset>Provincia *</fieldset>
<? Provincia::montarSelect(0, "requerido") ?>

                    <fieldset>Ciudad *</fieldset>
                    <input type="text" name="ciudad" class="requerido"/>

                    <fieldset>Teléfono Fijo</fieldset>
                    <input type="text" name="telefono"/>

                    <fieldset>Móvil *</fieldset>
                    <input type="text" name="movil" class="requerido"/>

                    <fieldset>Universidad de Procedencia *</fieldset>
                    <input type="text" name="universidad" class="requerido"/>

                    <? if (isset($_REQUEST["tarifa"]) && $_REQUEST["tarifa"] == 1) { ?>
                        <fieldset>¿Incluimos el manual?</fieldset>
                        <select name="manual" class="requerido">
                            <option value="0">--Selecciona--</option>
                            <option>Si</option>
                            <option>No</option>
                        </select>
                        <? } else { ?>
                        <input type="hidden" name="manual" value="Si"/>
                        <? } ?>
                    <fieldset>¿En cuál curso estás interesado? *</fieldset>
                    <select name="tiempo_curso" class="requerido">
                        <option value="0">Tipo de Curso (8 Meses) -> Los descuentos son hasta el 10 de Mayo</option>
                    <?
                    $tar = 0;
                    if (isset($_REQUEST["tarifa"])) {
                        $tar = $_REQUEST["tarifa"];
                    }
                    if ($tar != 2) {
                        ?>
                            <option>Plataforma Training: 699&euro; - 40% = (499&euro;)</option>
                            <option>Plataforma Training + Tutoría: 990&euro; - 25% = (792&euro;)</option>
                    <? }
                    if ($tar != 1) {
                        ?>
                            <option>Curso Premium: 1395&euro; - 40% = (996&euro;)</option>
                    <? } ?>
                    </select>
                    <fieldset><input type="checkbox" name="chkpolitica" class="requerido"/> He leido y acepto los términos de la <a href="politica.php">Política de Privacidad</a></fieldset>
                    <fieldset>Comentarios</fieldset>
                    <textarea name="comentarios" id="txtcomentarios"></textarea>

                    <input type="submit" value="Enviar" class="botonValidar animable"
                           style="background: none repeat scroll 0 0 #191003; border: 0 none; color: #FBF8E9;
                           padding: 5px; margin-top: 10px; cursor: pointer;"/>
                </form>
                <p class="pdatos" style=" text-align:left;">
                    El abono del curso se realizará en:<br />
                    Numero de cuenta <strong>0075-8979-20-0600250827
                        BANCO POPULAR.</strong><br /><br />
                    Una vez recibamos el pago, tendrás acceso completo a la plataforma a la cuenta de correo y clave facilitada
                    en el formulario.<br /><br />
                    Para resolver cualquier duda, te podemos atender sin compromiso en:<br />
                    C/ Ramón Cabanillas, Nº. 16, 1ro., 15702,<br />
                    Santiago de Compostela<br />
                    Telf/Fax: + 34 881 975 197<br />
                    Movil: + 34 669 924 856
                </p>
                <div style="display: table; margin:0 auto;">
                    <? if ($tar != 2) { ?>
                        <div class="bloquecontratar">
                            <h2>Personal Training</h2>
                        </div>
                <? }
                if ($tar != 1) {
                    ?>
                        <div class="bloquecontratar">
                            <h2>Training Premium</h2>
                        </div>                
                <? } ?>
                </div>
                <div class="clear20"></div>
                <p class="pdatos" style=" text-align:left;">
                    <b>Nota:</b> Posibilidad de Fraccionar en dos pagos el CURSO PREMIUM<br/>
                    hasta el 10 de Mayo del 2016<br/>
                    1er PAGO (50%): para oficializar la matrícula<br/>
                    2do PAGO (50%)
                </p>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
