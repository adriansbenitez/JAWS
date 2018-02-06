<div id="ventana">
    <form method="post" action="funciones/controlador.php" id="confirmaRegistro">
        <input type="hidden" name="accion" value="registrar"/>
        
        <input type="hidden" name="nombre"/>
        <input type="hidden" name="apellidos"/>
        <input type="hidden" name="email"/>
        <input type="hidden" name="clave"/>
        <input type="hidden" name="interes">
        
        <? require '../recaptcha-php-1.11/recaptchalib.php';
        $publickey = "6LfvEt0SAAAAAPXaPKJ5WfCvmVyaj_dbTbLdiY-2"; // you got this from the signup page
        echo recaptcha_get_html($publickey); ?>
        <input type="submit" onclick="registrar()" id="botonregistrar" value="CONFIRMAR"/>
    </form>
</div>