<? include '../trozos/session.php';
seguridad("nodemo");

$idUnidad = $_GET["id_unidad"];
$idUsuario = $_SESSION["usuario"]->getIdUsuario();
if (isset($_REQUEST["id_usuario"]) && $_SESSION["usuario"]->getNivel() == 1) {
    $idUsuario = $_REQUEST["id_usuario"];
}
$bd = new BD();
$sql = "select a.id_anyo, a.nombre," .
    " (select COUNT(*) from temas t" .
    " inner join temas_usuarios tu on tu.id_tema = t.id_tema" .
    " inner join unidades u on u.id_tema = t.id_tema" .
    " inner join anyos a1 on a1.id_unidad = u.id_unidad" .
    " where a1.id_anyo = a.id_anyo and tu.id_usuario = ?) activado," .
    " (select COUNT(a2.id_anyo) from usuarios_anyos a2 where a2.id_usuario = ?" .
    " and a2.id_anyo = a.id_anyo) deshabilitado" .
    " from anyos a where a.id_unidad = ?" .
    " order by nombre";

$bd->setConsulta($sql);
$parametros = array($idUsuario, $idUsuario, $idUnidad);
$bd->ejecutar($parametros); ?>

<ul class="listadmin categoriasli">
    <? while ($it = $bd->resultado()) {
        $clase = " activado ";
        if ($it["deshabilitado"] == 1 || $it["activado"] == 0) {
            $clase = " bloqueado ";
        } ?>
        <li class="<? echo $clase; ?> curso_click">            
            <? echo $it["nombre"]; ?>
            <? if($_SESSION["usuario"]->getNivel() == 1){ ?>
                <img src="css/icos/adminbloques.png" style="float: right; cursor: pointer"
                    usuario="<? echo $idUsuario; ?>" activado="<? echo $it["deshabilitado"]; ?>"
                    id_anyo="<? echo $it["id_anyo"]?>" onclick="deshabilitaAnyo(this)"/>
            <? } ?>
        </li>
    <? } ?>
</ul>