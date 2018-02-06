<? include '../trozos/session.php';

$bd = new BD();

if(!isset($_SESSION["usuario"])){
    die();
}

if ($_SESSION["usuario"]->getNivel() == 1){
    $bd->setConsulta("select p.id_unidad, p.nombreUnidad nombre, COUNT(id_pregunta) total_preguntas
        from v_preguntas_por_entidad p
	where p.id_tema = ?
	group by p.id_unidad, p.nombreUnidad order by nombre");
    $bd->ejecutar($_POST["id_tema"]);
}else{
    $bd->setConsulta("select p.id_unidad, p.nombreUnidad nombre, COUNT(id_pregunta) total_preguntas, c.cuarto
 from v_preguntas_por_entidad p inner join cursos c on c.id_curso = p.id_curso
 where p.id_tema = ? and p.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?)
 group by p.id_unidad, p.nombreUnidad, c.cuarto order by nombre");
    $bd->ejecutar(array($_POST["id_tema"], $_SESSION["usuario"]->getIdUsuario()));
}
?><ul class="listadmin categoriasli"><?
    while($it = $bd->resultado()){?>
        <li>
            <? if($_SESSION["usuario"]->getNivel() == 1 || $it["cuarto"]){?>
                <img src="css/icos/icoplus.png" height="23" border="0" class="show lista-temas" align="absmiddle"
                rel="<? echo $it["id_unidad"]; ?>" onclick="expandeUnidades(this)"/>
            <? } ?>
            <span class="contador">(Nº Preguntas:<? echo $it["total_preguntas"]; ?>)</span>
            <? echo $it["nombre"]; ?>
            <input type="checkbox" id="Unidad<? echo $it["id_unidad"]; ?>" onclick="cambiaUnidad(this)"
            value="<? echo $it["id_unidad"] ?>" class="check-unidad"
            <? if($_POST["check"] == "true"){ echo "checked";} ?>
                preg="<? echo $it["total_preguntas"]; ?>"/>
        </li>
        <? if($_SESSION["usuario"]->getNivel() == 1 || $it["cuarto"]){ ?>
            <div id="dentroUnidad<? echo $it["id_unidad"]; ?>"></div>
        <? } ?>
    <? } ?>
</ul>