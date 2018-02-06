<? include 'trozos/session.php';
require_once 'clases/Categoria.php';
seguridad("admin");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
<? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <? $queEs = "Curso";
            $nivel = 1;
            $elid = 0;

            if (isset($_GET["id_curso"])) {
                $queEs = "Asignatura";
                $nivel = 2;
                $elid = $_GET["id_curso"];
            }

            if (isset($_GET["id_tema"])) {
                $queEs = "Unidad Temática";
                $nivel = 3;
                $elid = $_GET["id_tema"];
            }

            if (isset($_GET["id_unidad"])) {
                $queEs = "Año (nivel 4)";
                $nivel = 4;
                $elid = $_GET["id_unidad"];
            } ?>
            <div class="contplataforma">

                <h1>Añadir <? echo $queEs ?><img src="css/icos/icobloques.png" /></h1>
                <a href="listcursos.php" class="botonoscuro">&lt;&lt; Atrás</a>
                <hr />

                <form action="funciones/controlador.php"  id="frmcrearcurso" class="frmadmin" method="post">
                    <input type="hidden" name="nivel" value="<? echo $nivel; ?>"/>
                    <input type="hidden" name="elid" value="<? echo $elid; ?>"/>
                    <input type="hidden" name="accion" value="altaCategoria"/>
                    <label>Denominacion de la Entidad:</label>
                    <input type="text" name="nombre" class="requerido" />
                    <div class="clear"></div>
                    <label>Codigo:</label>
                    <input type="text" name="codigo"/>
                    <div class="clear"></div>

                    <? if($nivel < 3){ ?>
                        <label>Precio: <input type="text" name="precio" class="auto" /></label>
                        <div class="clear"></div>
                    <? } ?>
                        
                    <? if ($nivel == 1){ ?>
                        <label>Visible hasta 4 nivel (para exámenes):</label>
                        <input type="checkbox" name="cuarto"
                            style="margin-right: 0px; transform: scale(1.5,1.5);"/>
                        <div class="clear"></div>
                    <? } ?>

                    <input type="submit" value="Agregar" />

                </form>
                <? include 'trozos/leyenda.php'; ?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>