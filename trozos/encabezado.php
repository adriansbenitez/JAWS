<noscript>
    <div id="advicenoscriptshadow"></div>
    <div id="advicenoscript">
        <span>ATENCIÓN:</span> Necesitas habilitar JavaScript para poder utilizar la plataforma Inspiracle.<br /><br />
        Si tu navegador no es compatible, descarga gratis cualquiera de los siguientes navegadores:<br /><br />
        <a href="https://www.google.com/intl/es/chrome/" target="_blank">
            <img src="css/icos/chrome.png" title="Chrome" alt="Chrome" /></a>
        <a href="http://www.mozilla.org/es-ES/firefox/fx/" target="_blank">
            <img src="css/icos/firefox.png" title="Firefox"  alt="Firefox" /></a>
        <a href="http://windows.microsoft.com/es-ES/internet-explorer/products/ie/home" target="_blank">
            <img src="css/icos/ie.png" title="Internet Explorer" alt="Internet Explorer" /></a>
        <a href="http://www.opera.com/" target="_blank">
            <img src="css/icos/opera.png" title="Opera" alt="Opera" /></a>
        <a href="http://www.apple.com/es/safari/" target="_blank">
            <img src="css/icos/safari.png" title="Safary" alt="Safary" /></a>
    </div>
</noscript>
<? if (isset($_SESSION["aviso"])) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#shadow').css({'display': 'block'});
        });
    </script>
    <div id="shadow">
        <div id="error">
            <img src="css/icos/close.png" id="cerrar" />
                <? echo $_SESSION["aviso"]; ?>
            <div class="clear"></div>
	</div>
    </div>
<? $_SESSION["aviso"] = null;
} ?>
<? if (isset($_SESSION["usuario"])) {
    if($_SESSION["usuario"]->getNivel() ==1){ ?>
        <style type="text/css">
            #header #logo{
                display: none;
            }
        </style>
    <? }
} ?>
    
<div id="header">
    <div class="wrapper">
        <a href="index.php"><img src="css/images/logoinspiracle.jpg" id="logo" /></a>
        <ul>
            <?
            $nivel = 0;
            if (isset($_SESSION["usuario"])) {
                $nivel = $_SESSION["usuario"]->getNivel();
            }
            switch ($nivel) {
                case "0":
                    ?>
                    <li><a class="transition03" href="objetivos.php">OBJETIVOS</a></li>
                    <li><a class="transition03" href="tarifas.php">TARIFAS</a></li>
                    <li><a class="transition03" href="contacto.php">CONTACTO</a></li>
            <? break;
                case "1": ?>
                    <li><a class="transition03" href="globalstats.php">ESTADISTICAS</a></li>
                    <li><a class="transition03" href="perfiles.php">PERFILES</a></li>
                    <li><a class="transition03" href="listcursos.php">CURSOS</a></li>
                    <li><a class="transition03" href="listexamenes.php">EXÁMENES</a></li>
                    <li><a class="transition03" href="calendario_general.php">CALENDARIO</a></li>
                    <li><a class="transition03" href="grupos.php">GRUPOS</a></li>
            <? break;
                case "2": ?>
                    <li><a class="transition03" href="perfil.php">PERFIL</a></li>
                    <li><a class="transition03" href="listexamenes.php">EXÁMENES</a></li>
                    <li><a class="transition03" href="bloques.php">BLOQUES</a></li>
            <? break;
                case "3": ?>
                    <li><a class="transition03" href="perfil.php">PERFIL</a></li>
                    <li><a class="transition03" href="listexamenes.php">EXAMENES</a></li>
                    <li><a class="transition03" href="bloques.php">BLOQUES</a></li>            
            <? break;
                case "4": ?>
                    <li><a class="transition03" href="perfil.php">PERFIL</a></li>
                    <li><a class="transition03" href="listexamenes.php">EXAMENES</a></li>
                    <li><a class="transition03" href="bloques.php">BLOQUES</a></li>
                    <li><a class="transition03" href="tutoria.php">TUTORÍA</a></li>
            <? } ?>
        </ul>
        <script type="text/javascript" src="js/cookiesAndAnalytic.js?ver=1.1"></script>
        <link rel="stylesheet" type="text/css" href="css/cookies.css"/>
        <? if (!isset($_SESSION["usuario"])) { ?>
            <form action="funciones/controlador.php" method="post" id="frmlogin">
                <input type="hidden" name="accion" value="login"/>
                <input type="text" name="email" placeholder="Email" title="Email" class="requerido"/>
                <input type="password" name="clave" placeholder="Clave" class="requerido"/>
                <input type="submit" value="Acceder" /><br />
                <a href="recuperacontra.php" id="btnrecuperacontra">¿has olvidado tu contraseña?</a>
            </form>
        <? } else { ?>
            <div id="username">
                <span><? echo $_SESSION["usuario"]->getNombre(); ?></span>
                <a href="funciones/controlador.php?accion=logout"><img src="css/icos/logoff.png" /></a>
            </div>
        <? } ?>
    </div>
</div>