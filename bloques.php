<? include 'trozos/session.php';
 require_once 'clases/Curso.php';
seguridad('usuario');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <? include 'trozos/head.php'; ?>
        <title>Plataforma de Formacion Inspiracle</title>
    </head>
    <body>
        <? include 'trozos/encabezado.php'; ?>
	<div id="content">
            <div class="contplataforma">
                <h1>Bloques <img src="css/icos/icobloques.png" /></h1>
                <h2>Bloques Disponibles</h2>
                <? $curso = new Curso(null, null, null);
                $listaCursos = Curso_BD::bloquesDisponibles($_SESSION["usuario"]->getIdUsuario());
                foreach ($listaCursos as $curso){ ?>
                    <div class="bloquecontratar">
                        <a href="contratar-curso.php?id_curso=<? echo $curso->getIdCurso(); ?>" class="botonoscuro">COMPRAR</a> 
                        <h2><? echo $curso->getNombre(); ?></h2>
                        <span class="precio"><? echo number_format($curso->getPrecio(), 2, '.', "'"); ?><span>€</span></span>
                    </div>
                <? } ?>
                <div class="clear"></div>
                <br />
                <h2>Mis Bloques</h2>
                <? $listaCursos = Curso_BD::bloquesContratados($_SESSION["usuario"]->getIdUsuario());
                foreach ($listaCursos as $curso){?>
                    <div class="bloquecontratar">
                        <h2><?=$curso->getNombre()?></h2>
                        <?/*<span class="precio"><? echo number_format($curso->getPrecio(), 2, '.', "'"); ?><span>€</span></span>*/?>
                    </div>
                <? } ?>
            </div>
            <div class="clear"></div>
        </div>
        <? require_once 'trozos/pie.php'; ?>
    </body>
</html>
