<? include '../trozos/session.php';

$bd = new BD();

if(!isset($_SESSION["usuario"])){
    die();
}

if ($_SESSION["usuario"]->getNivel() != 1){
    $bd->setConsulta("select p.id_anyo, p.nombreAnyo nombre, COUNT(id_pregunta) total_preguntas".
        " from v_preguntas_por_entidad p".
        " where p.id_unidad = ?".
        " group by p.id_anyo, p.nombreAnyo order by nombre");
    $bd->ejecutar($_POST["id_unidad"]);
}else{
    $bd->setConsulta("select p.id_anyo, p.nombreAnyo nombre, COUNT(id_pregunta) total_preguntas".
        " from v_preguntas_por_entidad p".
        " where p.id_unidad = ?".
        " and p.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?)".
        " group by p.id_anyo, p.nombreAnyo  order by nombre");
    $bd->ejecutar(array($_POST["id_unidad"], $_SESSION["usuario"]->getIdUsuario()));
}
?><ul class="listadmin categoriasli"><?
    while($it = $bd->resultado()){?>
        <li>
            <span class="contador">(Nº Preguntas:<? echo $it["total_preguntas"]; ?>)</span>
            <? echo $it["nombre"]; ?>
            <input type="checkbox" id="Unidad<? echo $it["id_anyo"]; ?>" onclick="cambiaAnyo()"
            value="<? echo $it["id_anyo"] ?>" class="check-anyo"
            <? if($_POST["check"] == "true"){ echo "checked";} ?>
                preg="<? echo $it["total_preguntas"]; ?>"/>
        </li>
    <? } ?>
</ul>