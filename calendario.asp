<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<%seguridad%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/plantilla.dwt.asp" codeOutsideHTMLIsLocked="false" -->
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/totalvalidator-2.0.css" rel="stylesheet" type="text/css" />
<link href="css/jquery.onebyone.css" rel="stylesheet" type="text/css" />
<link href="css/animate.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="js/eventCalendar_v042/css/eventCalendar.css" type="text/css"/>
<link rel="stylesheet" href="js/eventCalendar_v042/css/eventCalendar_theme_responsive.css" type="text/css"/>
<link rel="stylesheet" href="js/jquery-ui-1.9.2.custom/css/ui-lightness/jquery-ui-1.9.2.custom.min.css" type="text/css"/>	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--#include file="inc/funcionesBD.asp" -->
<!--#include file="inc/funciones.asp" -->
<!-- InstanceBeginEditable name="doctitle" -->
<title>Calendario - Plataforma de Formacion Inspiracle</title>
<%seguridad()%>
<%seguridadnivel("Admin")%>
<!-- InstanceEndEditable -->
<% if(request("nuevo_periodo") = "1" then
    
end if %>
</head>
<body>
	<div id="header">
    	<div class="wrapper">
        	<a href="default.asp"><img src="css/images/logoinspiracle.jpg" id="logo" /></a>
        	<ul>
				<%=cargamenu()%>
            </ul>
			<%=login()%>
        </div>
    </div>
	<div id="content">
<!-- InstanceBeginEditable name="head" -->
	<div class="contplataforma">
    	<h1>Períodos <img src="css/icos/icocalendario.png" /></h1>
        <hr />
        <form method="post" class="frmadmin" action="calendario.asp">
            <input type="hidden" name="id_usuario" value="<%=request("id_usuario") %>"/>
            <input type="hidden" name="nuevo_periodo" value="1"/>
            <label>Titulo</label>
            <input type="text" name="txttitulo" id="txttitulo" title="vacio" />
            <div class="clear"></div>            
            <label>Fecha</label>
            <input type="text" name="txtfecha" id="txtfechainicio" class="auto" title="fecha" autocomplete="off" />
            <div class="clear"></div>
            <input class="botonoscuro" type="submit" value="Crear Evento" />
            <div class="clear"></div>
        </form>
        <hr />
		<div id="eventCalendarCalendarLine"></div>
		<div class="clear"></div>
    </div>
<!-- InstanceEndEditable -->
<div class="clear"></div>
</div>


	<div id="footer" class="transition03">
    	<div class="wrapper">
			<ul class="contents">
            	<li><a href="contacto.asp">Contacto</a></li>
            	<li><a href="aviso.asp">Aviso Legal</a></li>
            	<li><a href="politica.asp">Politica de Privacidad</a></li>
            </ul>
			<ul class="social">
            	<li><a href="http://www.facebook.com/inspiracle.santiago" target="_blank"><img src="css/images/socialfacebook.jpg" /></a></li>
            	<li><a href="http://twitter.com/Inspiracle" target="_blank"><img src="css/images/socialtwitter.jpg" /></a></li>
            	<li><a href="http://es.linkedin.com/pub/iliana-perdomo-l%C3%B3pez/53/49a/143" target="_blank"><img src="css/images/sociallinkedin.jpg" /></a></li>
            	<li><a href="http://tienda.inspiracle.es/" target="_blank"><img src="css/images/socialtienda.jpg" /></a></li>
            	<li><a href="http://www.inspiracle.es/" target="_blank"><img src="css/images/socialinspieracleofficialweb.jpg" /></a></li>
            	<li><a href="http://www.ecomputer.es"><img src="css/images/ecomputer.jpg" /></a></li>
            </ul>

    		<div class="clear"></div>
        </div>
    </div>
    <script src="js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script src="js/jquery.onebyone.min.js" type="text/javascript"></script>
    <script src="js/jquery.touchwipe.min.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>
    <script src="js/totalvalidator-2.0.js" type="text/javascript"></script>
    <script src="js/jquerycicle2.js" type="text/javascript"></script>
	<%muestraerror()%>
	<!-- InstanceBeginEditable name="js" -->
    
   	<script src="js/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui-1.9.2.custom/development-bundle/ui/i18n/jquery.ui.datepicker-es.js" type="text/javascript"></script>
	<script src="js/eventCalendar_v042/js/jquery.eventCalendar.js" type="text/javascript"></script>

    <script type="text/javascript">
		$(function() {
			$( "#txtfechainicio" ).datepicker($.datepicker.regional[ "es" ],{ dateFormat: "dd/mm/yy" });
		});
    </script>

    
    <!-- InstanceEndEditable -->


</body>
<!-- InstanceEnd --></html>
