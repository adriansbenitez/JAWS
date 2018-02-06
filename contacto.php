<? include 'trozos/session.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="barra-derecha">
                <iframe class="transition03" id="googlemaps" src="http://maps.google.es/maps?f=q&amp;source=s_q&amp;hl=es&amp;geocode=&amp;q=calle+Ram%C3%B3n+Cabanillas,+16,+santiago+de+compostela&amp;aq=&amp;sll=40.396764,-3.713379&amp;sspn=13.760919,19.753418&amp;ie=UTF8&amp;hq=&amp;hnear=R%C3%BAa+de+Ram%C3%B3n+Cabanillas,+16,+15701+Santiago+de+Compostela,+La+Coru%C3%B1a,+Galicia&amp;t=m&amp;ll=42.872505,-8.549252&amp;spn=0.022016,0.036478&amp;z=14&amp;iwloc=A&amp;output=embed" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" width="860" height="450"></iframe>
                <p id="pdatos">
                    C/ Ramón Cabanillas, No. 16, 1ro., 15702,<br />
                    Santiago de Compostela<br />
                    Telf/Fax: + 34 881 975 197<br />
                </p>
            </div>
            <div class="texto">
                <h2>Contacto</h2>
                <form id="contacto" method="post" action="funciones/controlador.php">
                    <div id="frmcontacto">
                        <input type="hidden" name="accion" value="contacto"/>
                        <fieldset>Nombre *</fieldset>
                        <input type="text" name="nombre" class="requerido"/>
                        <fieldset>Apellidos *</fieldset>
                        <input type="text" name="apellido" class="requerido"/>
                        <fieldset>Provincia *</fieldset>
                        <? Provincia::montarSelect(0, "requerido"); ?>
                        <fieldset>Ciudad *</fieldset>
                        <input type="text" name="ciudad" class="requerido" />
                        <fieldset>Dirección</fieldset>
                        <input type="text" name="direccion"/>
                        <fieldset>DNI</fieldset>
                        <input type="text" name="dni" class="requerido"/>
                        <fieldset>Teléfono</fieldset>
                        <input type="text" name="telefono"/>
                        <fieldset>Móvil</fieldset>
                        <input type="text" name="movil"/>
                        <fieldset>Email *</fieldset>
                        <input type="text" name="email" class="requerido"/>
                        <fieldset>Universidad de Procedencia</fieldset>
                        <input type="text" name="universidad"/>
                        <fieldset>Comentarios *</fieldset>
                        <textarea name="comentarios" class="requerido"></textarea>
                        <fieldset><input type="checkbox" name="chkpolitica" class="requerido"/> He leido y acepto los términos de la <a href="politica.php">Política de Privacidad</a></fieldset>
                    </div>
                    <div id="frmcontacto2">
                        <? require 'recaptcha-php-1.11/recaptchalib.php';
                            $publickey = "6LcIe_USAAAAALa4StYjP4is4Gdb7r2yCibbfNwb"; // you got this from the signup page
                            echo recaptcha_get_html($publickey); ?>
                        <input type="submit" value="Enviar" />
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>