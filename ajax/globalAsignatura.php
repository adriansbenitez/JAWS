<?

include '../trozos/session.php';

$id_asignatura = substr($_GET["idc"], 1);
$tipo = substr($_GET["idc"], 0, 1);
$id_usuario = $_GET["id_usuario"];

$totalok = 0;
$totalko = 0;
$totalnsnc = 0;

$sqlGraf1 = "";

$tempPeri1 = explode("per=", $_SERVER["HTTP_REFERER"]);

if (count($tempPeri1) != 1) {
    $per = $tempPeri1[1];
} else {
    $per = 0;
}

if ($per > 0) {
    $bd = new BD();
    $bd->setConsulta("select p.fecha, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha < p.fecha order by pr.fecha desc limit 1) antes, (select fecha from periodos pr where pr.id_usuario = ? and pr.fecha > p.fecha) despues from periodos p where p.id_periodo = ? limit 1");
    $bd->ejecutar(array($id_usuario, $id_usuario, $per));
    $it = $bd->resultado();

    $fecha = str_replace("-", "", $it["fecha"]);
    $despues = str_replace("-", "", $it["despues"]);
    $antes = str_replace("-", "", $it["antes"]);
} else if ($per == -1) {
    $bd = new BD();
    $bd->setConsulta("select MAX(fecha) as fecha from periodos where id_usuario = ?");
    $bd->ejecutar($id_usuario);
    $it = $bd->resultado();
    $fecha = str_replace("-", "", $it["fecha"]);
}

if ($per != 0) {
    if ($per == -1) {
        $sql = " and r.fecha >= $fecha";
    } else if ($antes != null && $despues != null) {
        $sql = " and r.fecha >= $antes and r.fecha <= $fecha";
    } else if ($antes == null && $despues != null) {
        $sql = " and r.fecha <= $fecha";
    } else if ($antes != null && $despues == null) {
        $sql .=" and r.fecha >= $antes and r.fecha <= $fecha";
    } else if ($antes == null && $antes == null) {
        $sql = " and r.fecha <= $fecha";
    }
}

if ($tipo == "t") {
    $sqlGraf1 = "select (select count(rrr.correcta) from resultados r inner join resultados_respuestas rr on rr.id_resultado = r.id_resultado inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta inner join preguntas p on p.id_pregunta = rrr.id_pregunta inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad where r.id_usuario = ? and rrr.correcta = 1 and u.id_tema = ?";
    if ($per != 0) {
        $sqlGraf1 .= $sql;
    }
    $sqlGraf1.= ") correctas," .
            " (select count(rrr.correcta) from resultados r inner join resultados_respuestas rr on rr.id_resultado = r.id_resultado inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta inner join preguntas p on p.id_pregunta = rrr.id_pregunta inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad where r.id_usuario = ? and rrr.correcta = 0 and u.id_tema = ?";
    if ($per != 0) {
        $sqlGraf1 .= $sql;
    }
    $sqlGraf1.= ") erradas," .
            " (select count(ep.id_pregunta) from examenes e inner join examenes_preguntas ep on ep.id_examen = e.id_examen inner join resultados r on r.id_examen = e.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad where r.id_usuario = ? and u.id_tema = ?";
    if ($per != 0) {
        $sqlGraf1 .= $sql;
    }
    $sqlGraf1 .=") todas";
} else {
    $sqlGraf1 = "select (select count(rrr.correcta) from resultados r inner join resultados_respuestas rr on rr.id_resultado = r.id_resultado inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta inner join preguntas p on p.id_pregunta = rrr.id_pregunta inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo where r.id_usuario = ? and rrr.correcta = 1 and a.id_unidad = ?";
    if ($per != 0) {
        $sqlGraf1 .= $sql;
    }
    $sqlGraf1 .= ") correctas," .
            " (select count(rrr.correcta) from resultados r inner join resultados_respuestas rr on rr.id_resultado = r.id_resultado inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta inner join preguntas p on p.id_pregunta = rrr.id_pregunta inner join anyos_preguntas ap on ap.id_pregunta = p.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo where r.id_usuario = ? and rrr.correcta = 0 and a.id_unidad = ?";
    if ($per != 0) {
        $sqlGraf1 .= $sql;
    }
    $sqlGraf1.= ") erradas," .
            " (select count(ep.id_pregunta) from examenes e inner join examenes_preguntas ep on ep.id_examen = e.id_examen inner join resultados r on r.id_examen = e.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo where r.id_usuario = ? and a.id_unidad = ?";
    if ($per != 0) {
        $sqlGraf1 .= $sql;
    }
    $sqlGraf1 .=") todas";
}

$bd = new BD();

$bd->setConsulta($sqlGraf1);

$parametros = array($id_usuario, $id_asignatura, $id_usuario, $id_asignatura, $id_usuario, $id_asignatura);
$bd->ejecutar($parametros);

if ($it = $bd->resultado()) {
    $totalok = $it["correctas"];
    $totalko = $it["erradas"];
    $totalnsnc = $it["todas"] - $it["erradas"] - $it["correctas"];
}

echo "[['Acertadas ($totalok)', $totalok],['Erradas ($totalko)', $totalko], ['Enblanco ($totalnsnc)', $totalnsnc]]";
