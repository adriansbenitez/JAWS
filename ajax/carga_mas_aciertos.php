<? require_once '../trozos/session.php';

seguridad("admin");

$preguntasMasAcertadas = "select p.id_pregunta, p.codigo, p.enunciado, count(rr.id_respuesta) as total from  preguntas p inner join respuestas re on re.id_pregunta = p.id_pregunta inner join resultados_respuestas rr on re.id_respuesta = rr.id_respuesta inner join anyos_preguntas ap on ap.id_pregunta = re.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema inner join resultados rl on rl.id_resultado = rr.id_resultado where rl.fecha >= ? and rl.fecha <= ? and";

if(!empty($_GET["fecha_inicio"])){
    $arrFecha = explode("/", $_GET["fecha_inicio"]);
    $fechaInicio = new Fecha($arrFecha[2] . "-" . $arrFecha[1] . "-" . $arrFecha[0] . " 00-00-00");
}else{
    $fechaInicio = Fecha::nuevaFecha(1, 1, 2000, 0);
}
                    
if(!empty($_GET["fecha_fin"])){
    $arrFecha = explode("/", $_GET["fecha_fin"]);
    $fechaFin = new Fecha($arrFecha[2] . "-" . $arrFecha[1] . "-" . $arrFecha[0] . " 00-00-00");
}else{
    $fechaFin = Fecha::nuevaFecha(31, 12, 2030, 0);
}

$parametros = array($fechaInicio->conFormatoAMDConHora(), $fechaFin->conFormatoAMDConHora());

switch ($_POST["nivel"]){
    
    
    case 1:
        $preguntasMasAcertadas .= " t.id_curso = ?";
        array_push($parametros, $_POST["entidad"]);
        break;

    case 2:
        $preguntasMasAcertadas .= " t.id_tema = ?";
        array_push($parametros, $_POST["entidad"]);
        break;

    case 3:
        $preguntasMasAcertadas .= " u.id_unidad = ?";
        array_push($parametros, $_POST["entidad"]);
        break;
}

$preguntasMasAcertadas .= " and re.correcta = 1 group by id_pregunta, codigo, enunciado order by total desc limit 20";

$bd = new BD();

$bd->setConsulta($preguntasMasAcertadas);
$bd->ejecutar($parametros);

?><ul class="listadmin"><?
    while($it = $bd->resultado()){ ?>
        <li class="peoresexamenes">
            <a href="editpregunta.php?id_pregunta=<?=$it["id_pregunta"]?>&id_anyo=-1"
                target="_blank"><img src="css/icos/admindetalles.png" /></a>
            <span><?=$it["total"]?> aciertos</span>
            <?=$it["enunciado"]?><br/><b>COD: <?=$it["codigo"]?></b>
            <div class="clear"></div>
        </li>
    <? } ?>
</ul>