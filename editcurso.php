<? include 'trozos/session.php';
require_once 'clases/Categoria.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
        <script type="text/javascript" src="js/recargasAdmin.js?ver=1.1"></script>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <?
                $cat = new Categoria(null, null, null, null, null, null);
                if (isset($_GET["id_curso"])) {
                    $cat = Categoria_BD::porId($_GET["id_curso"], 1);
                }
                if (isset($_GET["id_tema"])) {
                    $cat = Categoria_BD::porId($_GET["id_tema"], 2);
                }
                if (isset($_GET["id_unidad"])) {
                    $cat = Categoria_BD::porId($_GET["id_unidad"], 3);
                }
                if (isset($_GET["id_anyo"])) {
                    $cat = Categoria_BD::porId($_GET["id_anyo"], 4);
                }
                ?>
                <h1>Editar <?=$cat->nombreCategoria()?><br/><?=$cat->getNombreCompleto()?> <img src="css/icos/icobloques.png" /></h1>
                <a href="listcursos.php" class="botonoscuro">&lt;&lt; Atrás</a>
                <hr />

                <form action="funciones/controlador.php" class="frmadmin"  id="frmcrearcurso" method="post">
                    <input type="hidden" name="nivel" value="<? echo $cat->getNivel(); ?>"/>
                    <input type="hidden" name="elid" value="<? echo $cat->getId(); ?>"/>
                    <input type="hidden" name="accion" value="editarCategoria"/>
                    
                    <label>Nombre:</label>
                    <input type="text" name="nombre" class="requerido" value="<? echo $cat->getNombre(); ?>" />
                    <div class="clear"></div>

                    <? if ($cat->getNivel() < 3) { ?>
                        <label>Precio:</label>
                        <input type="text" name="precio" class="requerido" value="<? echo $cat->getPrecio(); ?>" />
                        <div class="clear"></div>
                    <? } ?>

                    <? if ($cat->getNivel() == 1){ ?>
                        <label>Visible hasta 4 nivel (para exámenes):</label>
                        <input type="checkbox" name="cuarto"
                            style="margin-right: 0px; transform: scale(1.5,1.5);"
                            <? if($cat->getCuarto()){echo 'checked="checked"';}?>/>
                        <div class="clear"></div>
                    <? } ?>

                    <input type="submit" value="Actualizar Curso" />
                </form>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
