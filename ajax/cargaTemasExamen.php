<? include '../trozos/session.php';

$bd = new BD();

if(!isset($_SESSION["usuario"])){
    die();
}

switch($_SESSION["usuario"]->getNivel()){
    case "1":
        $bd->setConsulta("select p.id_tema, p.nombreTema nombre, COUNT(id_pregunta) total_preguntas".
            " from v_preguntas_por_entidad p".
            " where p.id_curso = ?".
            " group by p.id_tema, p.nombreTema order by nombre");
        $bd->ejecutar($_POST["id_curso"]);
        break;
    case "2":
        $bd->setConsulta("select p.id_tema, p.nombreTema nombre, COUNT(id_pregunta) total_preguntas".
            " from v_preguntas_por_entidad p".
            " where p.id_curso = ?".
            " group by p.id_tema, p.nombreTema order by nombre");
        $bd->ejecutar($_POST["id_curso"]);
        break;
    default:
        $bd->setConsulta("select p.id_tema, p.nombreTema nombre,".
            " COUNT(id_pregunta) total_preguntas".
            " from v_preguntas_por_entidad p where p.id_curso = ? and".
            " p.id_tema in (select id_tema from temas_usuarios where id_usuario = ?)".
            " and p.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?)".
            " group by p.id_tema, p.nombreTema order by nombre");
        $bd->ejecutar(array($_POST["id_curso"], $_SESSION["usuario"]->getIdUsuario(), $_SESSION["usuario"]->getIdUsuario()));
        break;
}
?><ul class="listadmin categoriasli"><?
    while($it = $bd->resultado()){ ?>
        <li>
            <img src="css/icos/icoplus.png" height="23" border="0" class="show lista-temas" align="absmiddle"
                rel="<? echo $it["id_tema"]; ?>"  onclick="expandeTemas(this)"/>
            <span class="contador">(Nº Preguntas:<? echo $it["total_preguntas"]; ?>)</span>
                <? echo $it["nombre"]; ?>
            <input type="checkbox" id="Tema<? echo $it["id_tema"]; ?>" onclick="cambiaTema(this)"
                value="<? echo $it["id_tema"]; ?>" class="check-tema" <? if($_POST["check"] == "true"){ echo "checked";} ?>
                preg="<? echo $it["total_preguntas"]; ?>"/>
	</li>
	<div id="dentroTema<? echo $it["id_tema"]; ?>"></div>
    <? } ?>
</ul>