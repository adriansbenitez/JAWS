<?

include '../trozos/session.php';
seguridad("admin");

require_once '../clases/Unidad.php';

$listaUnidades = Unidad_BD::listaIdTema($_GET["id_tema"]);
$unidad = new Unidad(null, null, null, null);
?>
<ul class="listadmin categoriasli" data-tabla="categoria" data-columna="orden"><?
foreach ($listaUnidades as $unidad){?>
    <li class="seccionli">
        <img src="css/icos/icoplus.png" rel="<?=$unidad->getIdUnidad()?>"
             onclick="losUnidades(this)"
             height="23" border="0" class="show" align="absmiddle" />
        <span class="pbar" style="width:<?=$unidad->peso()?>%"><?=$unidad->peso()?>%</span>
        <span class="contador">(<?=$unidad->getTotalPreguntas()?>)</span>
        <?=$unidad->getNombre()?>
	<a href="funciones/controlador.php?accion=borrarCategoria&id_unidad=<?=$unidad->getIdUnidad()?>"
            onclick="return confirm('Confirma la eliminación de la entidad');">
            <img src="css/icos/delete.png" />
        </a>
        
        <a href="addcurso.php?id_unidad=<?=$unidad->getIdUnidad()?>"><img src="css/icos/adminadd.png" /></a>
        <a href="editcurso.php?id_unidad=<?=$unidad->getIdUnidad()?>"><img src="css/icos/adminedit.png" /></a>
        <div id="dentroUnidad<?=$unidad->getIdUnidad()?>"></div>
    </li>
<? } ?>
</ul>