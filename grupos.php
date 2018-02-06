<? include 'trozos/session.php';
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
        </style>        
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">    
            <div class="contplataforma">
                <h1>Grupos <img src="css/icos/icoperfil.png" /></h1>
                <a href="agregar_grupo.php" class="botonoscuro">+ Crear Nuevo Grupo</a>
                <div class="clear"></div>
                <div id="lista">
                    <ul class="listadmin">
                    <? $grupo = new Grupo(null, null, null);
                    $listaGrupos = Grupo_BD::listar();
                    foreach ($listaGrupos as $grupo){ ?>
                        <li>
                            <p class="clearfix"><?=$grupo->getNombre(); ?> - <?=$grupo->getTotalUsuarios()?> miembros</p>
                            <a href="escribirCorreo.php?id_grupo=<?=$grupo->getIdGrupo()?>">
                                <img src="css/icos/inputmail.png"/>
                            </a>
                            <a href="editar_grupo.php?id_grupo=<?=$grupo->getIdGrupo()?>">
                                <img src="css/icos/adminedit.png"/>
                            </a>

                            <a href="funciones/controlador.php?accion=borrar_grupo&id_grupo=<?=$grupo->getIdGrupo()?>">
                                <img src="css/icos/admindelete.png"/>
                            </a>
                        </li>
                    <? } ?>
                    </ul>
                </div>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
