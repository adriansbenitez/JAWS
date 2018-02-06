<? include '../trozos/session.php';

$bd = new BD();

$id_asignatura = substr($_GET["idc"], 1);
$tipo = substr($_GET["idc"], 0, 1);
$id_usuario = $_GET["id_usuario"];

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

$sqlGraf3 = "";

if($tipo == "t"){
    $sqlGraf3 = "select r.id_resultado, r.fecha, (select count(rrr.correcta) from resultados r1 inner join resultados_respuestas rr on rr.id_resultado = r1.id_resultado" .
" inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where rrr.correcta = 1 and r1.id_resultado = r.id_resultado) correctas," .
" (select count(rrr.correcta) from resultados r1 inner join resultados_respuestas rr on rr.id_resultado = r1.id_resultado" .
" inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where rrr.correcta = 0 and r1.id_resultado = r.id_resultado) incorrectas," .
" (select count(ep1.id_pregunta) from examenes_preguntas ep1 where ep1.id_examen = ep.id_examen) total_preguntas from resultados r inner join examenes_preguntas ep on ep.id_examen = r.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad inner join temas t on t.id_tema = u.id_tema" .
" where r.id_usuario = ? and t.id_tema = ?";
    if ($per != 0) {
        $sqlGraf3 .= $sql;
    }
    $sqlGraf3 .=" group by r.id_resultado, r.fecha".
    " order by fecha desc limit 5";
}else{
    $sqlGraf3 = "select r.id_resultado, r.fecha, (select count(rrr.correcta) from resultados r1 inner join resultados_respuestas rr on rr.id_resultado = r1.id_resultado" .
" inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where rrr.correcta = 1 and r1.id_resultado = r.id_resultado) correctas," .
" (select count(rrr.correcta) from resultados r1 inner join resultados_respuestas rr on rr.id_resultado = r1.id_resultado" .
" inner join respuestas rrr on rrr.id_respuesta = rr.id_respuesta where rrr.correcta = 0 and r1.id_resultado = r.id_resultado) incorrectas," .
" (select count(ep1.id_pregunta) from examenes_preguntas ep1 where ep1.id_examen = ep.id_examen) total_preguntas from resultados r inner join examenes_preguntas ep on ep.id_examen = r.id_examen inner join anyos_preguntas ap on ap.id_pregunta = ep.id_pregunta inner join anyos a on a.id_anyo = ap.id_anyo inner join unidades u on u.id_unidad = a.id_unidad where r.id_usuario = ? and u.id_unidad = ?";
    if ($per != 0) {
        $sqlGraf3 .= $sql;
    }
    $sqlGraf3 .= " group by r.id_resultado, r.fecha".
	" order by fecha desc limit 5";
}

$bd->setConsulta($sqlGraf3);

$parametros = array($id_usuario, $id_asignatura);

$bd->ejecutar($parametros);

$notas = "";
$fechas = "";
$cont2 = 0;


while($it = $bd->resultado()){
    $cont2++;
    if($it["total_preguntas"] == 0){
        $notas.= ",0";
    }else{
        $notas.= ",". number_format(((($it["correctas"]*3)-$it["incorrectas"])*100)/($it["total_preguntas"]*3), 2, ".", "");
    }

    $fechaTemp = explode("-", substr($it["fecha"], 0, 10));
    $fechas.=",'".$fechaTemp[2]."/".$fechaTemp[1]."/".$fechaTemp[0]."'";
}
        
if($cont2 > 0){
    $notas = substr($notas, 1);
    $fechas = substr($fechas, 1);
}

echo " var resp={notas: [$notas], fechas:[$fechas]}";