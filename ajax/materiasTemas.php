<? include '../trozos/session.php';
seguridad("nodemo");

$idCurso = $_GET["id_curso"];
$idUsuario = $_SESSION["usuario"]->getIdUsuario();
if (isset($_REQUEST["id_usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
    $idUsuario = $_REQUEST["id_usuario"];
}
$bd = new BD();
$sql = "select t.id_tema, t.nombre," .
        " (select COUNT(*)" .
        " from temas t1 inner join temas_usuarios tu on tu.id_tema = t1.id_tema" .
        " where t1.id_tema = t.id_tema and tu.id_usuario = ?) activa" .
        " from temas t where t.id_curso = ? and t.activo = 1";
$bd->setConsulta($sql);
$parametros = array($idUsuario, $idCurso);
$bd->ejecutar($parametros); ?>

<ul class="listadmin categoriasli">
    <? while ($it = $bd->resultado()) {
        $clase = " activado ";
        if ($it["activa"] == 0) {
            $clase = " bloqueado ";
        } ?>
        <li class="<? echo $clase; ?> curso_click">
            <img src="css/icos/icoplus.png" height="23" border="0" class="show" align="absmiddle"
                rel="<? echo $it["id_tema"]; ?>" rel2="<? echo $idUsuario; ?>" onclick="cargaTemasAdmin(this)"/>
            <? echo $it["nombre"]; ?>
            <? if ($_SESSION["usuario"]->getNivel() == 1) { ?>
                <img src="css/icos/adminbloques.png" onclick="activaTema(this)" activado="<? echo $it["activa"]; ?>"
                    usuario="<? echo $idUsuario; ?>"
                    id_tema="<? echo $it["id_tema"]?>" style="float: right; cursor: pointer;"/>
            <? } ?>
        </li>
        <div id="dentroTema<? echo $it["id_tema"]; ?>"></div>
    <? } ?>
</ul>