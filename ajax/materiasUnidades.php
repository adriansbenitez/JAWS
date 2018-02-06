<? include '../trozos/session.php';
seguridad("nodemo");

$idTema = $_GET["id_tema"];
$idUsuario = $_SESSION["usuario"]->getIdUsuario();
if (isset($_REQUEST["id_usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
    $idUsuario = $_REQUEST["id_usuario"];
}
$bd = new BD();
$sql = "select u.id_unidad, u.nombre," .
    " (select COUNT(*)" .
    " from temas t inner join temas_usuarios tu on tu.id_tema = t.id_tema" .
    " inner join unidades u1 on u1.id_tema = t.id_tema" .
    " where u1.id_unidad = u.id_unidad and tu.id_usuario = ?) activa" .
    " from unidades u where u.id_tema = ? and u.activo = 1";

$bd->setConsulta($sql);
$parametros = array($idUsuario, $idTema);
$bd->ejecutar($parametros); ?>

<ul class="listadmin categoriasli">
    <? while ($it = $bd->resultado()) {
        $clase = " activado ";
        if ($it["activa"] == 0) {
            $clase = " bloqueado ";
        } ?>
        <li class="<? echo $clase; ?> curso_click">
            <img src="css/icos/icoplus.png" height="23" border="0" class="show" align="absmiddle"
                rel="<? echo $it["id_unidad"]; ?>" rel2="<? echo $idUsuario; ?>" onclick="cargaAnyosAdmin(this)"/>
            <? echo $it["nombre"]; ?>
        </li>
        <div id="dentroUnidad<? echo $it["id_unidad"]; ?>"></div>
    <? } ?>
</ul>