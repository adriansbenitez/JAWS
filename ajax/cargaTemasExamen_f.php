<? require_once '../clases/Tema.php';
include '../trozos/session.php';

$bd = new BD();

if(!isset($_SESSION["usuario"])){
    die();
}

$bd->setConsulta("select count(todo.id_pregunta) total_preguntas, t.id_tema, t.nombre from (select DISTINCT r.id_usuario, ep.id_pregunta,(select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta inner join resultados r2 on r2.id_resultado = rr.id_resultado where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario) as correctas,	(select count(ep2.id_pregunta) from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario) as total, (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1) as id_anyo from resultados r inner join examenes e on e.id_examen = r.id_examen inner join examenes_preguntas ep on ep.id_examen = e.id_examen where r.id_usuario = ? group by r.id_usuario, ep.id_pregunta, id_anyo) as todo inner join anyos an on an.id_anyo = todo.id_anyo inner join unidades uni on uni.id_unidad = an.id_unidad inner join temas t on t.id_tema = uni.id_tema where todo.total * 0.9 > todo.correctas and t.id_curso = ? GROUP by t.id_tema, t.nombre UNION select count(todo.id_pregunta) total_preguntas, t.id_tema, t.nombre from (select DISTINCT r.id_usuario, ep.id_pregunta,(select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta inner join resultados r2 on r2.id_resultado = rr.id_resultado where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario) as correctas,(select count(ep2.id_pregunta) from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario) as total, (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1) as id_anyo from resultados r inner join examenes e on e.id_examen = r.id_examen inner join examenes_preguntas ep on ep.id_examen = e.id_examen where r.id_usuario = ? group by r.id_usuario, ep.id_pregunta, id_anyo) as todo inner join preguntas_virtual pv on pv.id_pregunta = todo.id_pregunta inner JOIN unidades uni on uni.id_unidad = pv.id_unidad inner join temas t on t.id_tema = uni.id_tema where todo.total * 0.9 > todo.correctas and t.id_curso = ? GROUP by t.id_tema, t.nombre order by nombre");
$bd->ejecutar(array($_SESSION["usuario"]->getIdUsuario(), $_POST["id_curso"],$_SESSION["usuario"]->getIdUsuario(), $_POST["id_curso"]));
$listaTemas = array();
$tema = new Tema(null, null, null, null);

while($it = $bd->resultado()){
    if($tema->getIdTema() == $it["id_tema"]){
        $tema->setTotalPreguntas($tema->getTotalPreguntas()+$it["total_preguntas"]);
    }else{
        if($tema->getIdTema() != null){
            array_push($listaTemas, $tema);
        }
        $tema = new Tema($it["id_tema"], $it["nombre"], $it["total_preguntas"]);
    }
}
array_push($listaTemas, $tema);

?><ul class="listadmin categoriasli"><?
    foreach ($listaTemas as $tema){ ?>
        <li>
            <img src="css/icos/icoplus.png" height="23" border="0" class="show lista-temas" align="absmiddle"
                 rel="<?= $tema->getIdTema() ?>"  onclick="expandeTemas(this)"/>
            <span class="contador">(Nº Preguntas:<?= $tema->getTotalPreguntas() ?>)</span>
                <? echo $tema->getNombre(); ?>
            <input type="checkbox" id="Tema<?= $tema->getIdTema() ?>" onclick="cambiaTema(this)"
                   value="<?= $tema->getIdTema() ?>" class="check-tema" <? if($_POST["check"] == "true"){ echo "checked";} ?>
                   preg="<?= $tema->getTotalPreguntas() ?>"/>
	</li>
        <div id="dentroTema<?= $tema->getIdTema() ?>"></div>
    <? } ?>
</ul>