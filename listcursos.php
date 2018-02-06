<? include 'trozos/session.php';
 require_once 'clases/Curso.php';
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
                <h1>Gestor de Cursos <img src="css/icos/icobloques.png" /></h1>
                <a href="listpreguntasPerdidas.php" class="botonoscuro">Consultar preguntas perdidas</a>
                <a href="addcurso.php" class="botonoscuro">+ Añadir</a>
                <hr />
                <div class="clear"></div>

                <ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden">
	
		<? $curso = new Curso(null, null, null);
                $listaCursos = Curso_BD::listar();
                foreach ($listaCursos as $curso){?>
                    <li class="seccionli">
                        <img src="css/icos/icoplus.png" rel="<? echo $curso->getIdCurso(); ?>"
                            height="23" border="0" class="show" align="absmiddle" onclick="losCursos(this)"/>
			    <span class="pbar" style=" width: 100%"> 100%</span>
                            <span class="contador">(<?=$curso->getTotalPreguntas()?>)</span>
			<?=$curso->getNombre()?>
                            <a href="funciones/controlador.php?accion=borrarCategoria&id_curso=<?=$curso->getIdCurso(); ?>" onclick="return confirm('Confirma la eliminación de la entidad');">
                            <img src="css/icos/delete.png" />
                        </a>
                        
			<a href="addcurso.php?id_curso=<?=$curso->getIdCurso()?>"><img src="css/icos/adminadd.png" /></a>
                        <a href="editcurso.php?id_curso=<?=$curso->getIdCurso()?>"><img src="css/icos/adminedit.png" /></a>
                        <div id="dentroCurso<?=$curso->getIdCurso()?>">
		</li>
            <? } ?>
	</ul> 
                <? include 'trozos/leyenda.php';?>
            </div>
            <div class="clear"></div>
        </div>
        <? include 'trozos/pie.php'; ?>
    </body>
</html>
