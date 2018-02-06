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
            <div class="texto">
                <div style="display: table; margin: 0 auto;">
                    <h2>Recuperación de contraseña</h2>
                    <form id="contacto" method="post" action="funciones/controlador.php">
                        <div id="frmcontacto">
                            <input type="hidden" name="accion" value="recupera_clave"/>
                            <fieldset style="text-align: center;">Indica el correo electrónico con el que te has registrado:</fieldset>
                            <div style="display: table; margin: 0 auto;">
                                <input type="text" name="email" class="requerido" style="width: 250px;"/>
                            </div>
                            <div class="clear" style="height: 40px;"></div>
                            <div style="display: table; margin: 0 auto;">
                                <? require 'recaptcha-php-1.11/recaptchalib.php';
                                $publickey = "6LcIe_USAAAAALa4StYjP4is4Gdb7r2yCibbfNwb"; // you got this from the signup page
                                echo recaptcha_get_html($publickey); ?>
                            </div>
                            <div class="clear" style="height: 40px;"></div>
                            <input type="submit" value="Enviar" style="text-align: center;margin: 0px auto !important;
                                   width: 100px !important; display: block;"/>
                        </div>
                    </form>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>