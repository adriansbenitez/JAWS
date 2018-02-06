<? include 'trozos/session.php';
include 'trozos/menuPerfil.php';
include 'clases/Tema.php';
seguridad("usuario");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Tutoría</title>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
        <div id="content">
            <div class="contplataforma">
                <h1>Tutoría <img src="css/icos/icoexamenes.png" /></h1>
                <h2>Formulario de Dudas Curso Inspiracle PREMIUM</h2>
                <hr />
                <div class="clear"></div>

                <form action="funciones/controlador.php" id="frmcrearexamen" class="frmadmin" method="post">
                    <input type="hidden" name="accion" value="enviarTutoria"/>
                    <div class="frminfo">
                        <p class="frmtitulo">Para que podamos responder con eficacia:</p>
                        <ul>
                            <li><span>1)</span> Envía un máximo de 5-6 dudas en cada formulario; envía varios formularios si lo necesitas.</li>
                            <li><span>2)</span> Si la duda se refiere a alguna pregunta de un exámen (Test), lo adecuado es que la transcribas en el formulario
                                indicando: Examen, Pregunta, (Año) si es de exámenes oficiales.</li>
                            <li><span>3)</span> En la medida de lo posible, intenta especificar claramente qué es lo que no entiendes.</li>
                        </ul>
                    </div>

                    <div class="clear15"></div>

                    <label>Asignatura</label>
                    <select name="asignatura">
                        <option value="0">-- Selecciona --</option>
                        <? $lista = Tema_BD::listaIdCurso($_SESSION["usuario"]->getInteresado());
                        foreach ($lista as $tema) { ?>
                            <option value="<? echo $tema->getNombre(); ?>"><? echo $tema->getNombre(); ?></option>
                        <? } ?>
                    </select>
                    <div class="clear"></div>

                    <label>Duda de contenidos</label><input name="dudacontenidos" type="text"/>
                    <div class="clear"></div>

                    <label>Duda de exámenes</label><input name="dudaexamenes" type="text"/>
                    <div class="clear15"></div>
                    <div class="clear15"></div>

                    <label>Escribe aquí tus dudas y, al final haz click en enviar datos</label>             

                    <textarea name="dudas"></textarea>
                    <div class="clear"></div>
                    <hr />
                    <input type="submit" value="Enviar Consulta" />
                    <div class="clear"></div>
                </form>
            </div>        
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
