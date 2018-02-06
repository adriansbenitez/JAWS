<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/plantilla.dwt.asp" codeOutsideHTMLIsLocked="false" -->
<head>
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/totalvalidator-2.0.css" rel="stylesheet" type="text/css" />
<link href="css/jquery.onebyone.css" rel="stylesheet" type="text/css" />
<link href="css/animate.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--#include file="inc/funcionesBD.asp" -->
<!--#include file="inc/funciones.asp" -->
<!-- InstanceBeginEditable name="doctitle" -->
<title>Plataforma de Formacion Inspiracle</title>
<!-- InstanceEndEditable -->


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
	<%if request("estadocategoria") <> "" then
		actualizarBD "categoria", "activo", request("estadocategoria"), " Where id ="&request("id")
		response.Redirect("cursos.asp")	
	end if%>
    
    <div class="contplataforma">
        <h1>Gestor de Cursos <img src="css/icos/icobloques.png" /></h1>
            <a href="addcurso.asp?idpadre=0" class="botonoscuro">+ Añadir</a>
            <hr />
            <div class="clear"></div>
		<%function CargaListadoCategorias(MM_idpadre,nivel)
        
            Set RS_CargaCategorias= Server.CreateObject("ADODB.Recordset")
            AbrirRS RS_CargaCategorias, "categoria", " WHERE idpadre = "&MM_idpadre  , " ORDER  by nombre ASC", ""
            if RS_CargaCategorias.EOF then
                exit function
            end if%>

            <ul class="listadmin categoriasli">
            <%While NOT RS_CargaCategorias.EOF%>
                <li>
                	<span class="pbar" style=" width:<%=replace(RS_CargaCategorias.fields.item("peso").value,",",".")%>%"><%=RS_CargaCategorias.fields.item("peso").value%>%</span>
                    <span class="contador">(<%=contarCampo("pregunta","id","where idcategoria ="&RS_CargaCategorias.fields.item("id").value )%>)</span>
					<%=RS_CargaCategorias.fields.item("nombre").value%>
					<%if RS_CargaCategorias.fields.item("activo").value = "1" then
                        icono="admindelete.png"
                        proximoestado = "0"
                    else 
                        icono="adminalert.png"
                        proximoestado = "1"
                    end if%>
                    <a href="?estadocategoria=<%=proximoestado%>&id=<%=RS_CargaCategorias.fields.item("id").value%>">
                        <img src="css/icos/<%=icono%>" />
                    </a>
                	<a href="listpreguntas.asp?id=<%=RS_CargaCategorias.fields.item("id").value%>"><img src="css/icos/adminquestion.png" /></a>
                    <a href="addcurso.asp?idpadre=<%=RS_CargaCategorias.fields.item("id").value%>"><img src="css/icos/adminadd.png" /></a>
                    <a href="editcurso.asp?id=<%=RS_CargaCategorias.fields.item("id").value%>"><img src="css/icos/adminedit.png" /></a>
                </li>
                
                <%CargaListadoCategorias RS_CargaCategorias.Fields.Item("id").Value,nivel%>
        		
                <%RS_CargaCategorias.MoveNext()
            Wend%>
            
            </ul>
            <%CerrarRS RS_CargaCategorias
        end function
        CargaListadoCategorias 0,0%>
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
    <script src="js/jquery-1.8.2.min.js"></script>
    <script src="js/jquery.onebyone.min.js"></script>
    <script src="js/jquery.touchwipe.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/totalvalidator-2.0.js"></script>
	<%muestraerror()%>

</body>
<!-- InstanceEnd --></html>
