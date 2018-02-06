<? require_once '../trozos/session.php';

seguridad("admin");

$masBlancas = "select p.id_pregunta, p.codigo, p.enunciado, count(p.id_pregunta) - (select count(r.id_pregunta) from respuestas r inner join resultados_respuestas rr on rr.id_respuesta = r.id_respuesta where r.id_pregunta = p.id_pregunta) total from preguntas p inner join examenes_preguntas ep on ep.id_pregunta = p.id_pregunta inner join resultados r on r.id_examen = ep.id_examen inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema where r.fecha >= ? and r.fecha <= ? and";

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
    case 0:
        $masBlancas .= " 1=1";
    case 1:
        $masBlancas .= " t.id_curso = ?";
        array_push($parametros, $_POST["entidad"]);
        break;

    case 2:
        $masBlancas .= " t.id_tema = ?";
        array_push($parametros, $_POST["entidad"]);
        break;

    case 3:
        $masBlancas .= " u.id_unidad = ?";
        array_push($parametros, $_POST["entidad"]);
        break;
}

$masBlancas .= " group by id_pregunta, codigo, enunciado order by total desc limit 20";

$bd = new BD();

$bd->setConsulta($masBlancas);
$bd->ejecutar($parametros);

?><ul class="listadmin"><?
    while($it = $bd->resultado()){ ?>
        <li class="peoresexamenes">
            <a href="editpregunta.php?id_pregunta=<?=$it["id_pregunta"]?>&id_anyo=-1"
                target="_blank"><img src="css/icos/admindetalles.png" /></a>
            <span><?=$it["total"]?> blancas</span>
            <?=$it["enunciado"]?><br/><b>COD: <?=$it["codigo"]?></b>
            <div class="clear"></div>
        </li>
    <? } ?>
</ul>