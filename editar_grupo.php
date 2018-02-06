<? include 'trozos/session.php';
require_once 'clases/Grupo.php';
seguridad("admin");
$grupo = Grupo_BD::porId($_GET["id_grupo"]);
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
                <h1>Editar Grupo <img src="css/icos/icoperfil.png" /></h1>
                <div class="clear"></div>
                <a href="grupos.php" class="botonoscuro">&lt;&lt; Atrás</a>
                <div class="clear"></div>
                <br/>
                <form action="funciones/controlador.php" method="post" class="frmadmin">
                    <input type="hidden" name="accion" value="editar_grupo"/>
                    <input type="hidden" name="id_grupo" value="<?=$grupo->getIdGrupo(); ?>"/>
                    <input type="hidden" name="lista_usuarios" value="" id="lista_usuarios"/>
                    <label>Nombre del Grupo </label>
                    <input name="nombre" type="text" value="<?=$grupo->getNombre()?>"/>
                    <div class="clear"></div>
                    
                    <input type="submit" class="botonoscuro derecha alta-cal" value="Modificar" style="margin: 10px auto;"/>
                </form>
                <div class="clear20"></div>
                <hr/>
                <? $listaUsuarios = Usuario_BD::listarParaCalendario();
                $total = count($listaUsuarios);
                $cuenta = 0;
                $parte = 1; ?>
                <div class="lista-usuarios-cal" style="margin: 30px auto 0;">
                    <? foreach ($listaUsuarios as $usuario) { ?>
                        <div class="usuario">
                            <?= $usuario->getApellido() ?>, <?= $usuario->getNombre() ?>
                            <input type="checkbox"
                                class="inter_<?= $usuario->getInteresado() ?>"
                                value="<?= $usuario->getIdUsuario() ?>" <?
                                foreach ($grupo->getListaAlumnos() as $marcado){
                                    if($marcado == $usuario->getIdUsuario()){
                                        echo 'checked="checked"';
                                    }
                                } ?>/>
                        </div>
                    <? } ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
<? include 'trozos/pie.php'; ?>
    </body>
</html>