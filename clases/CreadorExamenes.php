<?php

require_once 'BD.php';
require_once 'Usuario.php';
require_once 'Tema.php';
require_once 'Unidad.php';
require_once 'Anyo.php';

class CreadorExamenes {

    private $preguntasCursos;
    private $preguntasTemas;
    private $preguntasUnidades;
    private $preguntasAnyos;
    private $totalPreguntas;
    private $listaPreguntas;
    private $titulo;
    private $tiempo;
    private $fecha;
    private $fechaFin;
    private $listaGrupos;

    function __construct() {
        $this->preguntasCursos = 0;
        $this->preguntasTemas = array();
        $this->preguntasUnidades = array();
        $this->preguntasAnyos = array();
        $this->totalPreguntas = 0;
        $this->listaPreguntas = array();
    }

    public function getPreguntasCursos() {
        return $this->preguntasCursos;
    }

    public function getPreguntasTemas() {
        return $this->preguntasTemas;
    }

    public function getPreguntasUnidades() {
        return $this->preguntasUnidades;
    }

    public function getPreguntasAnyos() {
        return $this->preguntasAnyos;
    }

    public function getTotalPreguntas() {
        return $this->totalPreguntas;
    }

    public function getListaPreguntas() {
        return $this->listaPreguntas;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getTiempo() {
        return $this->tiempo;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function getListaGrupos() {
        return $this->listaGrupos;
    }

    //SET ----------------------------------------------------------------------
    public function setPreguntasCursos($preguntasCursos) {
        $this->preguntasCursos = $preguntasCursos;
    }

    public function setPreguntasTemas($preguntasTemas) {
        $this->preguntasTemas = $preguntasTemas;
    }

    public function setPreguntasUnidades($preguntasUnidades) {
        $this->preguntasUnidades = $preguntasUnidades;
    }

    public function setPreguntasAnyos($preguntasAnyos) {
        $this->preguntasAnyos = $preguntasAnyos;
    }

    public function setTotalPreguntas($totalPreguntas) {
        $this->totalPreguntas = $totalPreguntas;
    }

    public function setListaPreguntas($listaPreguntas) {
        $this->listaPreguntas = $listaPreguntas;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setTiempo($tiempo) {
        $this->tiempo = $tiempo;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin;
    }

    public function setListaGrupos($listaGrupos) {
        $this->listaGrupos = $listaGrupos;
    }

    //PERSONALIZADOS -----------------------------------------------------------
    public function repartePesos() {
        $tema = new Tema(null, null, null, null);
        $preguntas = 0;
        
        foreach ($this->preguntasTemas as $tema) {
            $porcentaje = number_format(($tema->getTotalPreguntas() * 100) / $this->preguntasCursos, 2);
            $tema->setCantidadPreguntas(number_format(($porcentaje * $this->totalPreguntas) / 100, 2));
            $preguntas+=floor($tema->getCantidadPreguntas());
        }

        for ($i = $preguntas; $i < $this->totalPreguntas; $i++) {
            $maximo = 0;
            $temaMaximo = new Tema(null, null, null, null);
            foreach ($this->preguntasTemas as $tema) {
                $actual = $tema->getCantidadPreguntas() - floor($tema->getCantidadPreguntas());
                if ($maximo < $actual) {
                    $maximo = $actual;
                    $temaMaximo = $tema;
                }
            }
            $temaMaximo->setCantidadPreguntas(floor($temaMaximo->getCantidadPreguntas()) + 1);
        }

        foreach ($this->preguntasTemas as $tema) {
            $tema->setCantidadPreguntas(floor($tema->getCantidadPreguntas()));
        }

        $unidad = new Unidad(null, null, null, null);
        
        foreach ($this->preguntasTemas as $tema) {
            $preguntas = 0;
            foreach ($this->preguntasUnidades as $unidad) {
                if ($tema->getIdTema() == $unidad->getIdTema()) {
                    $porcentaje = number_format(($unidad->getTotalPreguntas() * 100) / $tema->getTotalPreguntas(), 2);
                    $unidad->setCantidadPreguntas(number_format(($porcentaje * $tema->getCantidadPreguntas()) / 100, 2));
                    $preguntas+=floor($unidad->getCantidadPreguntas());
                }
            }
            
            //echo "---------------------------------<br/><br/>";

            $maximo = 0;
            $unidadMaximo = new Unidad(null, null, null, null);
            for ($i = $preguntas; $i < $tema->getCantidadPreguntas(); $i++) {
                foreach ($this->preguntasUnidades as $unidad) {
                    if ($tema->getIdTema() == $unidad->getIdTema()) {
                        $actual = $unidad->getCantidadPreguntas() - floor($unidad->getCantidadPreguntas());
                        if ($maximo < $actual) {
                            $maximo = $actual;
                            $unidadMaximo = $unidad;
                        }
                    }
                }
                $unidadMaximo->setCantidadPreguntas(floor($unidadMaximo->getCantidadPreguntas()) + 1);
                
                $unidadMaximo = new Unidad(null, null, null, null);
                $maximo = 0;
            }
        }

        $preguntas = 0;
        foreach ($this->preguntasUnidades as $unidad) {
            $unidad->setCantidadPreguntas(floor($unidad->getCantidadPreguntas()));
            $preguntas+=$unidad->getCantidadPreguntas();
        }
    }

    public function preguntasIn() {
        $cadena = "";
        foreach ($this->listaPreguntas as $pregunta) {
            $ele = explode("_", $pregunta);
            $cadena.= $ele[0] . ",";
        }
        return $cadena . "0";
    }

    public function sorteaPreguntas() {
        $bd = new BD();

        $unidad = new Unidad(null, null, null, null);
        $anyo = new Anyo(null, null, null, null);

        if (count($this->preguntasAnyos) > 0) {

            foreach ($this->preguntasUnidades as $unidad) {
                $anyos = "";
                foreach ($this->preguntasAnyos as $anyo) {
                    if ($anyo->getIdUnidad() == $unidad->getIdUnidad()) {
                        if ($anyos != "") {
                            $anyos.=",";
                        }
                        $anyos.= $anyo->getIdAnyo();
                    }
                }
                $sql = "select distinct p.id_pregunta, a.id_anyo from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where a.id_anyo in($anyos) and p.activo = 1 and p.id_pregunta not in (";

                if ($unidad->getCantidadPreguntas() > 0) {
                    $arrListaAzar = array();
                    $arrAnyoPregunta = array();
                    
                    $bd->setConsulta($sql . $this->preguntasIn() . ")");
                    $bd->ejecutar();

                    while ($it = $bd->resultado()) {
                        array_push($arrListaAzar, $it["id_pregunta"]);
                        array_push($arrAnyoPregunta, $it["id_anyo"]);
                    }
                    $pilladas = 0;
                    $losIds="";
                    for ($i = 0; $i < $unidad->getCantidadPreguntas(); $i++) {
                        if (count($arrListaAzar) > 0) {
                            $azar = rand(0, count($arrListaAzar) - 1);
                            $arrNuevo = array();
                            $arrAnyoNuevo = array();
                            for ($c = 0; $c < count($arrListaAzar); $c++) {
                                if ($azar == $c) {
                                    if(CreadorExamenes::comprobar($this->listaPreguntas, $arrListaAzar[$c])){
                                        $losIds.=$arrListaAzar[$c]."_".$arrAnyoPregunta[$c]."|";
                                        array_push($this->listaPreguntas, $arrListaAzar[$c]."_".$arrAnyoPregunta[$c]);
                                    }
                                } else {
                                    array_push($arrNuevo, $arrListaAzar[$c]);
                                    array_push($arrAnyoNuevo, $arrAnyoPregunta[$c]);
                                }
                            }
                            $arrListaAzar = $arrNuevo;
                            $arrAnyoPregunta = $arrAnyoNuevo;
                        }
                        $pilladas++;
                    }
                }
            }
        } else {

            $sql = "select distinct p.id_pregunta, a.id_anyo from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where u.id_unidad = ? and p.activo = 1 and p.id_pregunta not in (";

            foreach ($this->preguntasUnidades as $unidad) {
                if ($unidad->getCantidadPreguntas() > 0) {
                    $arrListaAzar = array();
                    $arrAnyoPregunta = array();
                    
                    $bd->setConsulta($sql . $this->preguntasIn() . ")");
                    $bd->ejecutar($unidad->getIdUnidad());
                    
                    while ($it = $bd->resultado()) {
                        array_push($arrListaAzar, $it["id_pregunta"]);
                        array_push($arrAnyoPregunta, $it["id_anyo"]);
                    }

                    for ($i = 0; $i < $unidad->getCantidadPreguntas(); $i++) {
                        if (count($arrListaAzar) > 0) {
                            $azar = rand(0, count($arrListaAzar) - 1);
                            $arrNuevo = array();
                            $arrAnyoNuevo = array();
                            for ($c = 0; $c < count($arrListaAzar); $c++) {
                                if ($azar == $c) {
                                    if(CreadorExamenes::comprobar($this->listaPreguntas, $arrListaAzar[$c])){
                                        array_push($this->listaPreguntas, $arrListaAzar[$c]."_".$arrAnyoPregunta[$c]);
                                    }
                                } else {
                                    array_push($arrNuevo, $arrListaAzar[$c]);
                                    array_push($arrAnyoNuevo, $arrAnyoPregunta[$c]);
                                }
                            }
                            $arrListaAzar = $arrNuevo;
                            $arrAnyoPregunta = $arrAnyoNuevo;
                        }
                    }
                }
            }
        }
//        echo "Las preguntas son ".count($this->listaPreguntas);
//        foreach ($this->listaPreguntas as $pregunta){
//            echo $pregunta.",";
//        }
    }
    
    public static function comprobar($array1, $eleComp){
        $correcto = true;
        foreach ($array1 as $elemento1){
            $ele1 = explode("_", $elemento1)[0];
            if($eleComp == $ele1){
                $correcto = false;
            }
        }
        
        return $correcto;
    }

    public function sorteaPreguntasSimple() {
        $listaPreguntas = $this->getListaPreguntas();
        
        $listaTotal = array();
        
        for ($i = 0; $i < $this->totalPreguntas; $i++) {
            $listaNueva = array();
            $azar = rand(0, count($listaPreguntas) - 1);
            for ($c = 0; $c < count($listaPreguntas); $c++) {
                if ($azar == $c) {
                    array_push($listaTotal, $listaPreguntas[$c]);
                } else {
                    array_push($listaNueva, $listaPreguntas[$c]);
                }
            }
            $listaPreguntas = $listaNueva;
        }
        
        $this->listaPreguntas = $listaTotal;
    }

}

class CreadorExamenes_BD {

    public static function totalesPreguntasCurso($usuario, $idCurso) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();
        if ($usuario->getNivel() == 1 || $usuario->getNivel() == 2) {
            $bd->setConsulta("select COUNT(p.id_pregunta) total_preguntas from cursos c inner join temas t on t.id_curso = c.id_curso inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where c.id_curso = ? and p.activo = 1 and u.activo = 1 and t.activo = 1");
            $bd->ejecutar($idCurso);
            if ($it = $bd->resultado()) {
                $creadorExamen->setPreguntasCursos($it["total_preguntas"]);
            }

            $bd->setConsulta("select t.id_tema, COUNT(p.id_pregunta) total_preguntas from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where t.id_curso = ? and p.activo = 1 and u.activo = 1 and t.activo = 1 group by t.id_tema order by id_tema");
            $bd->ejecutar($idCurso);
            $arrTemas = array();
            while ($it = $bd->resultado()) {
                $tema = new Tema($it["id_tema"], null, $it["total_preguntas"]);
                array_push($arrTemas, $tema);
            }
            $creadorExamen->setPreguntasTemas($arrTemas);

            $bd->setConsulta("select u.id_unidad, u.id_tema, COUNT(p.id_pregunta) total_preguntas from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas t on t.id_tema = u.id_tema where t.id_curso = ? and p.activo = 1 and u.activo = 1 group by u.id_unidad, u.id_tema order by id_tema, id_unidad");
            $bd->ejecutar($idCurso);
            $arrUnidades = array();
            while ($it = $bd->resultado()) {
                $unidad = new Unidad($it["id_unidad"], null, 1, $it["total_preguntas"]);
                $unidad->setIdTema($it["id_tema"]);
                array_push($arrUnidades, $unidad);
            }
            $creadorExamen->setPreguntasUnidades($arrUnidades);
        } else {

            $bd->setConsulta("select COUNT(p.id_pregunta) total_preguntas from cursos c inner join temas t on t.id_curso = c.id_curso inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas_usuarios tu on tu.id_tema = t.id_tema where tu.id_usuario = ? and t.id_curso = ?");
            
            /*
            select COUNT(p.id_pregunta) total_preguntas
 from cursos c inner join temas t on t.id_curso = c.id_curso
 inner join unidades u on u.id_tema = t.id_tema
 inner join anyos a on a.id_unidad = u.id_unidad
 inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo
 inner join preguntas p on p.id_pregunta = ap.id_pregunta
 where c.id_curso in (select interesado from usuarios lu
 where id_usuario = ?) and t.id_tema in
 (select id_tema from temas_usuarios where id_usuario = ?)
 and c.id_curso = ? and c.activo = 1 and t.activo = 1 and u.activo = 1
 and p.activo = 1 and a.id_anyo not in
 (select id_anyo from usuarios_anyos where id_usuario = ?);
             */
            
            $parametros = array($usuario->getIdUsuario(), $idCurso);
            $bd->ejecutar($parametros);
            if ($it = $bd->resultado()) {
                $creadorExamen->setPreguntasCursos($it["total_preguntas"]);
            }

            $bd->setConsulta("select t.id_tema, COUNT(p.id_pregunta) total_preguntas from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where t.id_curso = ? and t.id_tema in (select id_tema from temas_usuarios where id_usuario = ?) and t.activo = 1 and u.activo = 1 and p.activo = 1 and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?) group by t.id_tema, t.nombre order by id_tema");
            $parametros = array($idCurso, $usuario->getIdUsuario(), $usuario->getIdUsuario());
            $bd->ejecutar($parametros);
            while ($it = $bd->resultado()) {
                $tema = new Tema($it["id_tema"], null, $it["total_preguntas"]);
                $arrTemas = $creadorExamen->getPreguntasTemas();
                array_push($arrTemas, $tema);
                $creadorExamen->setPreguntasTemas($arrTemas);
            }

            $bd->setConsulta("select u.id_unidad,u.id_tema, COUNT(p.id_pregunta) total_preguntas from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas t on t.id_tema = u.id_tema where t.id_curso = ? and t.id_tema in (select id_tema from temas_usuarios where id_usuario = ?) and u.activo = 1 and p.activo = 1 and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?) group by u.id_unidad, u.nombre, u.id_tema order by id_tema, id_unidad");
            $parametros = array($idCurso, $usuario->getIdUsuario(), $usuario->getIdUsuario());
            $bd->ejecutar($parametros);
            while ($it = $bd->resultado()) {
                $unidad = new Unidad($it["id_unidad"], null, 1, $it["total_preguntas"]);
                $unidad->setIdTema($it["id_tema"]);
                $arrUnidades = $creadorExamen->getPreguntasUnidades();
                array_push($arrUnidades, $unidad);
                $creadorExamen->setPreguntasUnidades($arrUnidades);
            }
        }
        return $creadorExamen;
    }

    public static function preguntasCursoFalladas($usuario, $idCurso) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();
        $bd->setConsulta("select todo.id_pregunta
from (
	select r.id_usuario, ep.id_pregunta,
 	(
		select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta
	    inner join resultados r2 on r2.id_resultado = rr.id_resultado
	    where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario
 	) as correctas,
	(
    	select count(ep2.id_pregunta)
		from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen
		where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario
	) as total,
    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1
        ) as id_anyo
	from resultados r
	inner join examenes e on e.id_examen = r.id_examen
	inner join examenes_preguntas ep on ep.id_examen = e.id_examen
	where r.id_usuario = ?
	group by r.id_usuario, ep.id_pregunta, id_anyo
) as todo inner join anyos an on an.id_anyo = todo.id_anyo
inner join unidades uni on uni.id_unidad = an.id_unidad
inner join temas t on t.id_tema = uni.id_tema
where todo.total * 0.9 > todo.correctas and t.id_curso = ?
UNION
select todo.id_pregunta
from (
	select r.id_usuario, ep.id_pregunta,
 	(
		select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta
	    inner join resultados r2 on r2.id_resultado = rr.id_resultado
	    where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario
 	) as correctas,
	(
    	select count(ep2.id_pregunta)
		from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen
		where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario
	) as total,
    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1
        ) as id_anyo
	from resultados r
	inner join examenes e on e.id_examen = r.id_examen
	inner join examenes_preguntas ep on ep.id_examen = e.id_examen
	where r.id_usuario = ?
	group by r.id_usuario, ep.id_pregunta, id_anyo
) as todo inner join preguntas_virtual pv on pv.id_pregunta = todo.id_pregunta
inner JOIN unidades uni on uni.id_unidad = pv.id_unidad
inner join temas t on t.id_tema = uni.id_tema
where todo.total * 0.9 > todo.correctas and t.id_curso = ?");
        $parametros = array($usuario->getIdUsuario(), $idCurso, $usuario->getIdUsuario(), $idCurso);
        $bd->ejecutar($parametros);
        $arrPreguntas = array();
        while ($it = $bd->resultado()) {
            array_push($arrPreguntas, $it["id_pregunta"]);
        }
        $creadorExamen->setListaPreguntas($arrPreguntas);

        return $creadorExamen;
    }

    public static function totalesPreguntasTema($usuario, $temas) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();

        $bd->setConsulta("select COUNT(p.id_pregunta) total_preguntas from cursos c inner join temas t on t.id_curso = c.id_curso inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where t.id_tema in ($temas) and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?)");
        $bd->ejecutar($usuario->getIdUsuario());
        if ($it = $bd->resultado()) {
            $creadorExamen->setPreguntasCursos($it["total_preguntas"]);
        }

        $bd->setConsulta("select t.id_tema, COUNT(p.id_pregunta) total_preguntas from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where t.id_tema in ($temas) and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?) group by t.id_tema, t.nombre order by id_tema");
        $bd->ejecutar($usuario->getIdUsuario());
        while ($it = $bd->resultado()) {
            $tema = new Tema($it["id_tema"], null, $it["total_preguntas"]);
            $arrTemas = $creadorExamen->getPreguntasTemas();
            array_push($arrTemas, $tema);
            $creadorExamen->setPreguntasTemas($arrTemas);
        }

        $bd->setConsulta("select u.id_unidad, u.id_tema, COUNT(p.id_pregunta) total_preguntas from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas t on t.id_tema = u.id_tema where t.id_tema in ($temas) and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?) group by u.id_unidad, u.nombre, u.id_tema order by id_tema, id_unidad");
        $bd->ejecutar($usuario->getIdUsuario());
        while ($it = $bd->resultado()) {
            $unidad = new Unidad($it["id_unidad"], null, 1, $it["total_preguntas"]);
            $unidad->setIdTema($it["id_tema"]);
            $arrUnidades = $creadorExamen->getPreguntasUnidades();
            array_push($arrUnidades, $unidad);
            $creadorExamen->setPreguntasUnidades($arrUnidades);
        }
        return $creadorExamen;
    }

    public static function preguntasTemasFalladas($usuario, $temas) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();
        $bd->setConsulta("select todo.id_pregunta
from (
	select distinct r.id_usuario, ep.id_pregunta,
 	(
		select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta
	    inner join resultados r2 on r2.id_resultado = rr.id_resultado
	    where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario
 	) as correctas,
	(
    	select count(ep2.id_pregunta)
		from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen
		where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario
	) as total,
    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1
        ) as id_anyo
	from resultados r
	inner join examenes e on e.id_examen = r.id_examen
	inner join examenes_preguntas ep on ep.id_examen = e.id_examen
	where r.id_usuario = ?
	group by r.id_usuario, ep.id_pregunta, id_anyo
) as todo inner join anyos an on an.id_anyo = todo.id_anyo
inner join unidades uni on uni.id_unidad = an.id_unidad
where todo.total * 0.9 > todo.correctas and uni.id_tema in(".$temas.")
UNION
select todo.id_pregunta
from (
	select distinct r.id_usuario, ep.id_pregunta,
 	(
		select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta
	    inner join resultados r2 on r2.id_resultado = rr.id_resultado
	    where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario
 	) as correctas,
	(
    	select count(ep2.id_pregunta)
		from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen
		where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario
	) as total,
    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1
        ) as id_anyo
	from resultados r
	inner join examenes e on e.id_examen = r.id_examen
	inner join examenes_preguntas ep on ep.id_examen = e.id_examen
	where r.id_usuario = ?
	group by r.id_usuario, ep.id_pregunta, id_anyo
) as todo inner join preguntas_virtual pv on pv.id_pregunta = todo.id_pregunta
inner JOIN unidades uni on uni.id_unidad = pv.id_unidad
where todo.total * 0.9 > todo.correctas and uni.id_tema in(".$temas.")");

        $parametros = array($usuario->getIdUsuario(), $usuario->getIdUsuario());
        
        $bd->ejecutar($parametros);
        $arrPreguntas = array();
        while ($it = $bd->resultado()) {
            array_push($arrPreguntas, $it["id_pregunta"]);
        }
        $creadorExamen->setListaPreguntas($arrPreguntas);

        return $creadorExamen;
    }

    public static function totalesPreguntasUnidades($usuario, $unidades) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();

        $bd->setConsulta("select COUNT(p.id_pregunta) total_preguntas from cursos c inner join temas t on t.id_curso = c.id_curso inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where u.id_unidad in ($unidades) and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?)");
        $bd->ejecutar($usuario->getIdUsuario());
        if ($it = $bd->resultado()) {
            $creadorExamen->setPreguntasCursos($it["total_preguntas"]);
        }

        $bd->setConsulta("select t.id_tema, COUNT(p.id_pregunta) total_preguntas from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where u.id_unidad in ($unidades) and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?) group by t.id_tema, t.nombre order by id_tema");
        $bd->ejecutar($usuario->getIdUsuario());
        while ($it = $bd->resultado()) {
            $tema = new Tema($it["id_tema"], null, $it["total_preguntas"]);
            $arrTemas = $creadorExamen->getPreguntasTemas();
            array_push($arrTemas, $tema);
            $creadorExamen->setPreguntasTemas($arrTemas);
        }

        $bd->setConsulta("select u.id_unidad, u.id_tema, COUNT(p.id_pregunta) total_preguntas from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas t on t.id_tema = u.id_tema where u.id_unidad in ($unidades) and a.id_anyo not in (select id_anyo from usuarios_anyos where id_usuario = ?) group by u.id_unidad, u.nombre, u.id_tema order by id_tema, id_unidad");
        $bd->ejecutar($usuario->getIdUsuario());
        while ($it = $bd->resultado()) {
            $unidad = new Unidad($it["id_unidad"], null, null, $it["total_preguntas"]);
            $unidad->setIdTema($it["id_tema"]);
            $arrUnidades = $creadorExamen->getPreguntasUnidades();
            array_push($arrUnidades, $unidad);
            $creadorExamen->setPreguntasUnidades($arrUnidades);
        }
        return $creadorExamen;
    }

    public static function preguntasUnidadesFalladas($usuario, $unidades) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();
        $bd->setConsulta("select todo.id_pregunta
from (
	select distinct r.id_usuario, ep.id_pregunta,
 	(
		select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta
	    inner join resultados r2 on r2.id_resultado = rr.id_resultado
	    where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario
 	) as correctas,
	(
    	select count(ep2.id_pregunta)
		from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen
		where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario
	) as total,
    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1
        ) as id_anyo
	from resultados r
	inner join examenes e on e.id_examen = r.id_examen
	inner join examenes_preguntas ep on ep.id_examen = e.id_examen
	where r.id_usuario = ?
	group by r.id_usuario, ep.id_pregunta, id_anyo
) as todo inner join anyos an on an.id_anyo = todo.id_anyo
where todo.total * 0.9 > todo.correctas and an.id_unidad in(".$unidades.")
UNION
select todo.id_pregunta
from (
	select distinct r.id_usuario, ep.id_pregunta,
 	(
		select count(rr.id_respuesta) from resultados_respuestas rr inner join respuestas res on res.id_respuesta = rr.id_respuesta
	    inner join resultados r2 on r2.id_resultado = rr.id_resultado
	    where res.id_pregunta = ep.id_pregunta and res.correcta = 1 and r2.id_usuario = r.id_usuario
 	) as correctas,
	(
    	select count(ep2.id_pregunta)
		from examenes_preguntas ep2 inner join resultados r2 on r2.id_examen = ep2.id_examen
		where ep2.id_pregunta = ep.id_pregunta and r2.id_usuario = r.id_usuario
	) as total,
    (select id_anyo from anyos_preguntas ap where ap.id_pregunta = ep.id_pregunta order by ap.id_anyo limit 1
        ) as id_anyo
	from resultados r
	inner join examenes e on e.id_examen = r.id_examen
	inner join examenes_preguntas ep on ep.id_examen = e.id_examen
	where r.id_usuario = ?
	group by r.id_usuario, ep.id_pregunta, id_anyo
) as todo inner join preguntas_virtual pv on pv.id_pregunta = todo.id_pregunta
where todo.total * 0.9 > todo.correctas and pv.id_unidad in(".$unidades.")");

        $parametros = array($usuario->getIdUsuario(), $usuario->getIdUsuario());
        
        $bd->ejecutar($parametros);
        
        $arrPreguntas = array();
        while ($it = $bd->resultado()) {
            array_push($arrPreguntas, $it["id_pregunta"]);
        }
        $creadorExamen->setListaPreguntas($arrPreguntas);

        return $creadorExamen;
    }

    public static function totalesPreguntasAnyos($anyos) {
        $bd = new BD();
        $creadorExamen = new CreadorExamenes();

        $bd->setConsulta("select COUNT(p.id_pregunta) total_preguntas from cursos c inner join temas t on t.id_curso = c.id_curso inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where a.id_anyo in ($anyos)");
        $bd->ejecutar();
        if ($it = $bd->resultado()) {
            $creadorExamen->setPreguntasCursos($it["total_preguntas"]);
        }

        $bd->setConsulta("select t.id_tema, COUNT(p.id_pregunta) total_preguntas from temas t inner join unidades u on u.id_tema = t.id_tema inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta where a.id_anyo in ($anyos) group by t.id_tema, t.nombre order by id_tema");
        $bd->ejecutar();
        while ($it = $bd->resultado()) {
            $tema = new Tema($it["id_tema"], null, $it["total_preguntas"]);
            $arrTemas = $creadorExamen->getPreguntasTemas();
            array_push($arrTemas, $tema);
            $creadorExamen->setPreguntasTemas($arrTemas);
        }

        $bd->setConsulta("select u.id_unidad, u.id_tema, COUNT(p.id_pregunta) total_preguntas from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas t on t.id_tema = u.id_tema where a.id_anyo in ($anyos) group by u.id_unidad, u.nombre, u.id_tema order by id_tema, id_unidad");
        $bd->ejecutar();
        while ($it = $bd->resultado()) {
            $unidad = new Unidad($it["id_unidad"], null, null, $it["total_preguntas"]);
            $unidad->setIdTema($it["id_tema"]);
            $arrUnidades = $creadorExamen->getPreguntasUnidades();
            array_push($arrUnidades, $unidad);
            $creadorExamen->setPreguntasUnidades($arrUnidades);
        }

        $sql = "select a.id_anyo, u.id_unidad, u.id_tema, COUNT(p.id_pregunta) total_preguntas from unidades u inner join anyos a on a.id_unidad = u.id_unidad inner join anyos_preguntas ap on ap.id_anyo = a.id_anyo inner join preguntas p on p.id_pregunta = ap.id_pregunta inner join temas t on t.id_tema = u.id_tema where a.id_anyo in ($anyos) group by a.id_anyo, u.id_unidad, u.nombre, u.id_tema order by id_tema, id_unidad";

        $bd->setConsulta($sql);
        $bd->ejecutar();

        while ($it = $bd->resultado()) {
            $anyo = new Anyo($it["id_anyo"], null, $it["total_preguntas"]);
            $anyo->setIdUnidad($it["id_unidad"]);
            $arrAnyos = $creadorExamen->getPreguntasAnyos();
            array_push($arrAnyos, $anyo);
            $creadorExamen->setPreguntasAnyos($arrAnyos);
        }

        return $creadorExamen;
    }

    public static function crear($creadorExamen) {
        $bd = new BD();

        $sql = "insert into examenes(autor, tiempo, titulo, activo, fecha, fecha_fin) values (?, ?, ?, 1, ";
        if ($creadorExamen->getFecha() != null) {
            $sql .= $creadorExamen->getFecha()->conFormatoAlta() . ", ";
        } else {
            $sql .= "now(), ";
        }

        if ($creadorExamen->getFechaFin() != null) {
            $sql .= $creadorExamen->getFechaFin()->conFormatoAlta() . " )";
        } else {
            $sql .= "null)";
        }

        $bd->setConsulta($sql);

        $parametros = array($_SESSION["usuario"]->getIdUsuario(), $creadorExamen->getTiempo(), $creadorExamen->getTitulo());

        $bd->ejecutar($parametros);
        
        if($bd->filasModificadas() == 0){
            return false;
        }

        $bd->setConsulta("select id_examen from examenes where autor = ? order by id_examen desc limit 1");
        $bd->ejecutar($_SESSION["usuario"]->getIdUsuario());
        $idExamen = 0;
        if ($it = $bd->resultado()) {
            $idExamen = $it["id_examen"];
        }


        if ($creadorExamen->getListaGrupos() != null) {
            $bd->setConsulta("insert into examenes_grupos (id_grupo, id_examen) values (?, ?)");
            foreach ($creadorExamen->getListaGrupos() as $idGrupo){
                $bd->ejecutar(array($idGrupo, $idExamen));
            }
            
        }

        $bd->setConsulta("insert into examenes_preguntas (id_examen, id_pregunta, orden, id_anyo) values (?, ?, ?, ?)");
        $i = 0;
        foreach ($creadorExamen->getListaPreguntas() as $pregunta) {
            $pregunta = str_replace("@", "_", $pregunta);
            $arPre = explode("_", $pregunta);
            if(count($arPre) > 1){
                $idAnyo = $arPre[1];
            }else{
                $idAnyo = null;
            }
            $parametros = array($idExamen, $arPre[0], $i, $idAnyo);
            $bd->ejecutar($parametros);
            $i++;
        }

        $bd->setConsulta("insert into usuarios_examenes (id_examen, id_usuario) values (?, ?)");
        if (!empty($_POST["listaAlumnos"])) {
            $listaAlumnos = explode(",", $_POST["listaAlumnos"]);
            foreach ($listaAlumnos as $idUsuario) {
                $parametros = array($idExamen, $idUsuario);
                $bd->ejecutar($parametros);
            }
        } else if ($_SESSION["usuario"]->getNivel() != 1) {
            $parametros = array($idExamen, $_SESSION["usuario"]->getIdUsuario());
            $bd->ejecutar($parametros);
        }
        
        return true;
    }

}
