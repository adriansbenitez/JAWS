<? include '../trozos/session.php';
seguridad("admin");
require_once '../clases/Tema.php';

$listaTemas = Tema_BD::listaIdCurso($_GET["id_curso"]);
$tema = new Tema(null, null, null, null);
?>
<ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden"><?
    foreach($listaTemas as $tema){?>
        <li class="seccionli">
            <img src="css/icos/icoplus.png" rel="<?=$tema->getIdTema()?>"
            height="23" border="0" class="show" align="absmiddle" onclick="losTemas(this)"/>
            <span class="pbar" style=" width: <?=$tema->peso()?>%"><?=$tema->peso()?>%</span>
            <span class="contador">(<?=$tema->getTotalPreguntas()?>)</span>
            <?=$tema->getNombre()?>
            <a href="funciones/controlador.php?accion=borrarCategoria&id_tema=<?=$tema->getIdTema()?>"
               onclick="return confirm('Confirma la eliminación de la entidad');">
                <img src="css/icos/delete.png" />
            </a>
            
            <a href="addcurso.php?id_tema=<?=$tema->getIdTema()?>"><img src="css/icos/adminadd.png" /></a>
            <a href="editcurso.php?id_tema=<?=$tema->getIdTema()?>"><img src="css/icos/adminedit.png" /></a>
            <div id="dentroTema<?=$tema->getIdTema()?>"></div>
        </li>
    <? } ?>
</ul>