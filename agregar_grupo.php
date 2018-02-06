<?
include 'trozos/session.php';
require_once 'clases/Grupo.php';
seguridad("admin");
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
            
            .lista-usuarios-cal .usuario{
                float: left;
                width: 240px;
            }
        </style>
        <link type="text/css" rel="stylesheet" href="css/calendario.css"/>
        <script type="text/javascript">
            $(document).ready(function(){
                $(".alta-cal").click(function(){
                    var cadena = "";
                    $('.lista-usuarios-cal .usuario input').each(function(indice, ele){
                        if($(ele).attr('checked') == 'checked'){
                            cadena+=$(ele).attr('value')+",";
                        }
                    });
                    
                    if(cadena.length > 0){
                        $('#lista_usuarios').val(cadena.substring(0, cadena.length-1));
                    }
                });
            });
        </script>
    </head>
    <body>
<? include 'trozos/encabezado.php'; ?>
        <div id="content">    
            <div class="contplataforma">
                <h1>Nuevo Grupo <img src="css/icos/icoperfil.png" /></h1>
                <div class="clear"></div>
                <form action="funciones/controlador.php" method="post" class="frmadmin">
                    <input type="hidden" name="accion" value="nuevo_grupo"/>
                    <input type="hidden" name="lista_usuarios" value="" id="lista_usuarios"/>

                    <label>Nombre del Grupo </label><input name="nombre" type="text"/>
                    <div class="clear"></div>
                    
                    <input type="submit" class="botonoscuro derecha alta-cal" value="Alta" style="margin: 10px auto;"/>
                </form>
                <div class="clear20"></div>
                <hr/>
                <? $listaUsuarios = Usuario_BD::listarParaCalendario();
                $total = count($listaUsuarios);
                $cuenta = 0;
                $parte = 1; ?>
                <div class="lista-usuarios-cal" style="margin: 0 auto;">
                    <? foreach ($listaUsuarios as $usuario) { ?>
                        <div class="usuario">
                            <?= $usuario->getApellido() ?>, <?= $usuario->getNombre() ?>
                            <input type="checkbox"
                                class="inter_<?= $usuario->getInteresado() ?>"
                                value="<?= $usuario->getIdUsuario() ?>"/>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
<? include 'trozos/pie.php'; ?>
    </body>
</html>