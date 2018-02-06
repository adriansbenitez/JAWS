<? include 'trozos/session.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <style type="text/css">
        </style>
        <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Correo <img src="css/icos/inputmail.png" /></h1>
                <hr/>
                <form method="post" action="funciones/controlador.php" class="frmadmin">
                    <input type="hidden" name="accion" value="escribirCorreo"/>
                    <? if(isset($_REQUEST["id_usuario"])){ ?><input type="hidden" name="id_usuario" value="<?=$_REQUEST["id_usuario"]?>"/><? } ?>
                    <? if(isset($_REQUEST["id_grupo"])){ ?><input type="hidden" name="id_grupo" value="<?=$_REQUEST["id_grupo"]?>"/><? } ?>
                    <label>Asunto</label>
                    <input type="text" name="asunto" class="requerido"/>
                    <div class="clear"></div>
                    <label>Texto</label>
                    <textarea class="editor" name="texto"></textarea>
                    <div class="clear"></div>
                    <input class="botonoscuro" value="Enviar" type="submit"/>
                </form>
                <div class="clear"></div>
                <br/>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>