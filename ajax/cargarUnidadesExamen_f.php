<? include '../trozos/session.php';
 require_once '../clases/Unidad.php';

$bd = new BD();

if(!isset($_SESSION["usuario"])){
    die();
}

$bd->setConsulta("select count(todo.id_pregunta) total_preguntas, uni.id_unidad, uni.nombre from (select DISTINCT r.id_usuario, ep.id_pregunta,(select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta inner join resultados r2 on r2.id_resultado = rr.id_resultado where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario) as correctas, (select count(ep2.id_pregunta) from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario) as total,    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1) as id_anyo from resultados r	inner join examenes e on e.id_examen = r.id_examen inner join examenes_preguntas ep on ep.id_examen = e.id_examen where r.id_usuario = ? group by r.id_usuario, ep.id_pregunta, id_anyo) as todo inner join anyos an on an.id_anyo = todo.id_anyo inner join unidades uni on uni.id_unidad = an.id_unidad where todo.total * 0.9 > todo.correctas and uni.id_tema = ? GROUP by uni.id_unidad, uni.nombre UNION select count(todo.id_pregunta) total_preguntas, uni.id_unidad, uni.nombre from (select DISTINCT r.id_usuario, ep.id_pregunta,(select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta inner join resultados r2 on r2.id_resultado = rr.id_resultado where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario) as correctas,(select count(ep2.id_pregunta) from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario) as total,(select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1) as id_anyo from resultados r	inner join examenes e on e.id_examen = r.id_examen inner join examenes_preguntas ep on ep.id_examen = e.id_examen where r.id_usuario = ? group by r.id_usuario, ep.id_pregunta, id_anyo) as todo inner join preguntas_virtual pv on pv.id_pregunta = todo.id_pregunta inner JOIN unidades uni on uni.id_unidad = pv.id_unidad where todo.total * 0.9 > todo.correctas and uni.id_tema = ? GROUP by uni.id_unidad, uni.nombre order by nombre");

$bd->ejecutar(array($_SESSION["usuario"]->getIdUsuario(), $_POST["id_tema"], $_SESSION["usuario"]->getIdUsuario(), $_POST["id_tema"]));

$listaUnidades = array();
$unidad = new Unidad(null, null, null, null);

while($it = $bd->resultado()){
    if($unidad->getIdUnidad() == $it["id_unidad"]){
        $unidad->setTotalPreguntas($unidad->getTotalPreguntas()+$it["total_preguntas"]);
    }else{
        if($unidad->getIdUnidad() != null){
            array_push($listaUnidades, $unidad);
        }
        $unidad = new Unidad($it["id_unidad"], $it["nombre"], true, $it["total_preguntas"]);
    }
}
array_push($listaUnidades, $unidad);

?><ul class="listadmin categoriasli"><?
    foreach ($listaUnidades as $unidad){?>
        <li>
            <span class="contador">(Nº Preguntas:<?= $unidad->getTotalPreguntas() ?>)</span>
            <?=$unidad->getNombre()?>
            <input type="checkbox" id="Unidad<?=$unidad->getIdUnidad()?>" onclick="cambiaUnidad(this)"
                value="<?=$unidad->getIdUnidad()?>" class="check-unidad"
            <? if($_POST["check"] == "true"){ echo "checked";} ?>
                preg="<?= $unidad->getTotalPreguntas() ?>"/>
        </li>
    <? } ?>
</ul>